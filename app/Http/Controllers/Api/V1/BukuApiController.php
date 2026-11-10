<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BukuResource;
use App\Models\Buku;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BukuApiController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $allowedSorts = ['judul', 'penulis', 'stok', 'tahun_terbit', 'created_at'];
        $sortBy = in_array($request->query('sort'), $allowedSorts) ? $request->query('sort') : 'created_at';
        $sortDir = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);

        $paginator = Buku::query()
            ->with('kategori')
            ->withCount('peminjamans')
            ->when($request->query('search'), function ($q, $s) {
                $q->where(function ($sub) use ($s) {
                    $sub->where('judul', 'like', "%{$s}%")
                        ->orWhere('penulis', 'like', "%{$s}%")
                        ->orWhere('isbn', 'like', "%{$s}%");
                });
            })
            ->when($request->query('kategori_id'), fn ($q, $k) => $q->where('kategori_id', (int) $k))
            ->when($request->boolean('tersedia'), fn ($q) => $q->where('stok', '>', 0))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        return $this->successResponse(
            data: BukuResource::collection($paginator->items()),
            message: 'Daftar buku berhasil diambil.',
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

    public function show(string $id): JsonResponse
    {
        $buku = Buku::with(['kategori', 'peminjamans'])->withCount('peminjamans')->find($id);

        if (!$buku) {
            return $this->notFound($id);
        }

        return $this->successResponse(new BukuResource($buku), 'Detail buku berhasil ditemukan.');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'isbn' => 'required|digits:13|unique:bukus,isbn',
            'judul' => 'required|string|min:5|max:200',
            'penulis' => 'required|string|max:150',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|between:1900,' . date('Y'),
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',
        ]);

        $buku = Buku::create($validated);
        $buku->load('kategori')->loadCount('peminjamans');

        return $this->createdResponse(
            data: new BukuResource($buku),
            locationUrl: url("/api/v1/bukus/{$buku->id}"),
            message: 'Buku baru berhasil dibuat.'
        );
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return $this->notFound($id);
        }

        $isPut = $request->isMethod('put');

        if ($isPut) {
            $validated = $request->validate([
                'isbn' => ['required', 'digits:13', Rule::unique('bukus', 'isbn')->ignore($buku->id)],
                'judul' => 'required|string|min:5|max:200',
                'penulis' => 'required|string|max:150',
                'penerbit' => 'required|string|max:100',
                'tahun_terbit' => 'required|integer|between:1900,' . date('Y'),
                'kategori_id' => 'required|exists:kategoris,id',
                'stok' => 'required|integer|min:0',
                'sinopsis' => 'nullable|string',
            ]);
            $validated['sinopsis'] = $request->input('sinopsis', null);
        } else {
            $validated = $request->validate([
                'isbn' => ['sometimes', 'required', 'digits:13', Rule::unique('bukus', 'isbn')->ignore($buku->id)],
                'judul' => 'sometimes|required|string|min:5|max:200',
                'penulis' => 'sometimes|required|string|max:150',
                'penerbit' => 'sometimes|required|string|max:100',
                'tahun_terbit' => 'sometimes|required|integer|between:1900,' . date('Y'),
                'kategori_id' => 'sometimes|required|exists:kategoris,id',
                'stok' => 'sometimes|required|integer|min:0',
                'sinopsis' => 'sometimes|nullable|string',
            ]);

            if (empty($validated)) {
                return $this->problemResponse(
                    'Bad Request',
                    'Permintaan PATCH wajib menyertakan minimal satu atribut yang ingin diperbarui.',
                    400,
                    'empty-patch-payload'
                );
            }
        }

        $buku->update($validated);
        $buku->load('kategori')->loadCount('peminjamans');

        return $this->successResponse(
            new BukuResource($buku),
            $isPut ? 'Seluruh atribut buku berhasil diganti (PUT).' : 'Atribut buku berhasil diperbarui sebagian (PATCH).'
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return $this->notFound($id);
        }

        $aktif = $buku->peminjamans()->where('status', 'dipinjam')->count();

        if ($aktif > 0) {
            return $this->problemResponse(
                'Conflict - Book Has Active Loans',
                "Buku '{$buku->judul}' tidak dapat dihapus karena masih dipinjam ({$aktif} peminjaman aktif).",
                409,
                'book-has-active-loans'
            );
        }

        $buku->delete();

        return $this->noContentResponse();
    }

    private function notFound(string $id): JsonResponse
    {
        return $this->problemResponse(
            'Resource Not Found',
            "Buku dengan ID '{$id}' tidak ditemukan.",
            404,
            'book-not-found'
        );
    }
}