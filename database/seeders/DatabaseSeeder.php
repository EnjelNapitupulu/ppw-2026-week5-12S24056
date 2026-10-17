<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun pustakawan uji
        User::create([
            'name' => 'Pustakawan Del',
            'email' => 'pustakawan@del.ac.id',
            'password' => 'password123',
        ]);

        // 2. Kategori
        foreach ([
            ['KT-001', 'Teknologi Informasi'],
            ['KT-002', 'Matematika'],
            ['KT-003', 'Fisika'],
            ['KT-004', 'Manajemen'],
            ['KT-005', 'Sains Data'],
        ] as [$kode, $nama]) {
            Kategori::create(['kode_kategori' => $kode, 'nama_kategori' => $nama]);
        }

        // 3. 20 buku acak dari factory
        Buku::factory(20)->create();

        // 4. Buku uji khusus
        $kategoriId = Kategori::first()->id;

        Buku::create([
            'isbn' => '9780000000000', 'judul' => 'Buku Uji Stok Nol',
            'penulis' => 'Tim Perpustakaan', 'penerbit' => 'Del Press',
            'tahun_terbit' => 2024, 'kategori_id' => $kategoriId, 'stok' => 0,
            'sinopsis' => 'Dipakai untuk menguji penolakan peminjaman saat stok habis.',
        ]);

        Buku::create([
            'isbn' => '9780000000001', 'judul' => 'Buku Uji Stok Satu',
            'penulis' => 'Tim Perpustakaan', 'penerbit' => 'Del Press',
            'tahun_terbit' => 2024, 'kategori_id' => $kategoriId, 'stok' => 1,
            'sinopsis' => 'Dipakai untuk menguji race condition dengan stok tersisa satu.',
        ]);

        // 5. Sampel transaksi peminjaman
        $buku = Buku::where('stok', '>', 2)->first();

        Peminjaman::create([
            'kode_pinjam' => 'PJM-20261001-0001', 'buku_id' => $buku->id,
            'nim_peminjam' => '12S23001', 'nama_peminjam' => 'Andi Simanjuntak',
            'tanggal_pinjam' => '2026-10-01', 'tanggal_jatuh_tempo' => '2026-10-08',
            'tanggal_kembali' => null, 'status' => 'dipinjam',
        ]);
        $buku->decrement('stok');

        Peminjaman::create([
            'kode_pinjam' => 'PJM-20260920-0001', 'buku_id' => $buku->id,
            'nim_peminjam' => '12S23002', 'nama_peminjam' => 'Rina Sitorus',
            'tanggal_pinjam' => '2026-09-20', 'tanggal_jatuh_tempo' => '2026-09-27',
            'tanggal_kembali' => '2026-09-25', 'status' => 'dikembalikan',
        ]);
    }
}