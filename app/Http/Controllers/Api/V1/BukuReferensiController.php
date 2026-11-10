<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BukuReferensiController extends Controller
{
    use ApiResponse;

    /** GET /api/v1/bukus/{id}/referensi-eksternal : SIPUS-Del sebagai Service Consumer */
    public function show(string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return $this->problemResponse(
                'Resource Not Found',
                "Buku dengan ID '{$id}' tidak ditemukan.",
                404,
                'book-not-found'
            );
        }

        $key = "ISBN:{$buku->isbn}";

        try {
            $response = Http::timeout(8)
                ->withUserAgent('SIPUS-Del/1.0 (praktikum PPW IT Del)')
                ->acceptJson()
                ->get('https://openlibrary.org/api/books', [
                    'bibkeys' => $key,
                    'format' => 'json',
                    'jscmd' => 'data',
                ]);
        } catch (ConnectionException $e) {
            // Timeout atau koneksi gagal (termasuk masalah sertifikat SSL di Windows)
            Log::warning('[EXTERNAL API][openlibrary]: ' . $e->getMessage());

            return $this->problemResponse(
                'Gateway Timeout',
                'Layanan eksternal OpenLibrary tidak dapat dihubungi (timeout atau koneksi gagal).',
                504,
                'external-timeout'
            );
        }

        if ($response->failed()) {
            return $this->problemResponse(
                'Bad Gateway',
                'Layanan eksternal OpenLibrary mengembalikan galat (HTTP ' . $response->status() . ').',
                502,
                'external-service-error'
            );
        }

        $data = $response->json($key);

        if (empty($data)) {
            return $this->problemResponse(
                'External Book Not Found',
                "ISBN {$buku->isbn} tidak ditemukan di OpenLibrary.",
                404,
                'external-book-not-found'
            );
        }

        return $this->successResponse(
            data: [
                'isbn' => $buku->isbn,
                'sumber' => 'OpenLibrary',
                'judul_eksternal' => $data['title'] ?? null,
                'penulis_eksternal' => collect($data['authors'] ?? [])->pluck('name')->all(),
                'penerbit_eksternal' => collect($data['publishers'] ?? [])->pluck('name')->all(),
                'tanggal_terbit' => $data['publish_date'] ?? null,
                'jumlah_halaman' => $data['number_of_pages'] ?? null,
                'sampul' => $data['cover']['medium'] ?? null,
                'tautan_pratinjau' => $data['url'] ?? null,
            ],
            message: 'Metadata eksternal buku berhasil diambil dari OpenLibrary.'
        );
    }
}