<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $links = [
            'self' => ['href' => url("/api/v1/bukus/{$this->id}"), 'method' => 'GET'],
            'update_penuh' => ['href' => url("/api/v1/bukus/{$this->id}"), 'method' => 'PUT'],
            'update_sebagian' => ['href' => url("/api/v1/bukus/{$this->id}"), 'method' => 'PATCH'],
            'hapus' => ['href' => url("/api/v1/bukus/{$this->id}"), 'method' => 'DELETE'],
            'referensi_eksternal' => ['href' => url("/api/v1/bukus/{$this->id}/referensi-eksternal"), 'method' => 'GET'],
        ];

        if ($this->stok > 0) {
            $links['pinjam_buku'] = ['href' => url('/api/v1/peminjamans'), 'method' => 'POST'];
        }

        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun_terbit' => (int) $this->tahun_terbit,
            'stok' => (int) $this->stok,
            'status_label' => $this->stok > 0 ? 'TERSEDIA' : 'HABIS',
            'sinopsis' => $this->sinopsis,
            'jumlah_peminjaman' => $this->whenCounted('peminjamans'),
            'kategori' => new KategoriResource($this->whenLoaded('kategori')),
            'riwayat_peminjaman' => PeminjamanResource::collection($this->whenLoaded('peminjamans')),
            '_links' => $links,
        ];
    }
}