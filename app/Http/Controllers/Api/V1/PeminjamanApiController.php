<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PeminjamanResource;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Traits\ApiResponse;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PeminjamanApiController extends Controller
{
    use ApiResponse;

    /** GET /api/v1/peminjamans?status=dipinjam&nim=12S23001 */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);

        $paginator = Peminjaman::query()
            ->with('buku')
            ->when($request->query('status') && in_array($request->query('status'), ['dipinjam', 'dikembalikan']),
                fn ($q) => $q->where('status', $request->query('status')))
            ->when($request->query('nim'),
                fn ($q, $nim) => $q->where('nim_peminjam', strtoupper(trim($nim))))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return $this->successResponse(
            data: PeminjamanResource::collection($paginator->items()),
            message: 'Daftar peminjaman berhasil diambil.',
            meta: [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total_items' => $paginator->total(),
                'total_pages' => $paginator->lastPage(),
                'links' => [
                    'first' => $paginator->url(1),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                    'last' => $paginator->url($paginator->lastPage()),
                ],
            ]
        );
    }

    /** GET /api/v1/peminjamans/{id} */
    public function show(string $id): JsonResponse
    {
        $pinjam = Peminjaman::with('buku')->find($id);

        if (!$pinjam) {
            return $this->notFound($id);
        }

        return $this->successResponse(new PeminjamanResource($pinjam), 'Detail peminjaman berhasil ditemukan.');
    }

    /** POST /api/v1/peminjamans : transaksi ACID + lockForUpdate */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim_peminjam' => 'required|string|min:6|max:15',
            'nama_peminjam' => 'required|string|min:3|max:150',
            'durasi_hari' => 'required|integer|between:1,30',
        ]);

        try {
            $pinjam = DB::transaction(function () use ($validated) {
                // Kunci baris buku: request lain pada buku ini harus antre
                $buku = Buku::where('id', $validated['buku_id'])->lockForUpdate()->firstOrFail();

                if ($buku->stok < 1) {
                    throw new \DomainException("Stok buku \"{$buku->judul}\" sedang habis.", 422);
                }

                $nim = strtoupper(trim($validated['nim_peminjam']));

                $masihPinjam = Peminjaman::where('buku_id', $buku->id)
                    ->where('nim_peminjam', $nim)
                    ->where('status', 'dipinjam')
                    ->exists();

                if ($masihPinjam) {
                    throw new \DomainException("NIM {$nim} masih meminjam buku ini dan belum mengembalikannya.", 409);
                }

                $buku->decrement('stok');

                return Peminjaman::create([
                    'kode_pinjam' => 'PJM-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),
                    'buku_id' => $buku->id,
                    'nim_peminjam' => $nim,
                    'nama_peminjam' => $validated['nama_peminjam'],
                    'tanggal_pinjam' => now()->toDateString(),
                    'tanggal_jatuh_tempo' => now()->addDays((int) $validated['durasi_hari'])->toDateString(),
                    'status' => 'dipinjam',
                ]);
            }, 3);

            $pinjam->load('buku');

            return $this->createdResponse(
                data: new PeminjamanResource($pinjam),
                locationUrl: url("/api/v1/peminjamans/{$pinjam->id}"),
                message: 'Peminjaman berhasil dicatat. Stok buku telah dikurangi.'
            );
        } catch (\DomainException $e) {
            return $this->domainProblem($e);
        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION][api-pinjam]: ' . $e->getMessage());
            return $this->problemResponse(
                'Internal Server Error',
                'Terjadi kesalahan database, peminjaman dibatalkan.',
                500,
                'database-error'
            );
        }
    }

    /** PATCH /api/v1/peminjamans/{id}/kembalikan */
    public function kembalikan(string $id): JsonResponse
    {
        try {
            $pinjam = DB::transaction(function () use ($id) {
                $pinjam = Peminjaman::where('id', $id)->lockForUpdate()->first();

                if (!$pinjam) {
                    throw new \DomainException("Peminjaman dengan ID '{$id}' tidak ditemukan.", 404);
                }

                if ($pinjam->status === 'dikembalikan') {
                    throw new \DomainException('Buku ini sudah dikembalikan sebelumnya.', 409);
                }

                $buku = Buku::where('id', $pinjam->buku_id)->lockForUpdate()->firstOrFail();

                $pinjam->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);
                $buku->increment('stok');

                return $pinjam;
            }, 3);

            $pinjam->load('buku');

            return $this->successResponse(
                new PeminjamanResource($pinjam),
                'Buku berhasil dikembalikan. Stok buku telah ditambah.'
            );
        } catch (\DomainException $e) {
            return $this->domainProblem($e);
        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION][api-kembali]: ' . $e->getMessage());
            return $this->problemResponse(
                'Internal Server Error',
                'Terjadi kesalahan database, pengembalian dibatalkan.',
                500,
                'database-error'
            );
        }
    }

    /** Petakan DomainException (kode = status HTTP) ke RFC 7807 */
    private function domainProblem(\DomainException $e): JsonResponse
    {
        $status = in_array($e->getCode(), [404, 409, 422]) ? $e->getCode() : 422;

        $meta = [
            404 => ['Resource Not Found', 'loan-not-found'],
            409 => ['Conflict', 'loan-conflict'],
            422 => ['Unprocessable Content', 'out-of-stock'],
        ][$status];

        return $this->problemResponse($meta[0], $e->getMessage(), $status, $meta[1]);
    }

    private function notFound(string $id): JsonResponse
    {
        return $this->problemResponse(
            'Resource Not Found',
            "Peminjaman dengan ID '{$id}' tidak ditemukan.",
            404,
            'loan-not-found'
        );
    }
}