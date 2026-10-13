<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $daftar = [
            ['KT-001', 'Teknologi Informasi'],
            ['KT-002', 'Matematika'],
            ['KT-003', 'Fisika'],
            ['KT-004', 'Manajemen'],
            ['KT-005', 'Sains Data'],
        ];

        foreach ($daftar as [$kode, $nama]) {
            Kategori::create(['kode_kategori' => $kode, 'nama_kategori' => $nama]);
        }

        Buku::factory(20)->create();
    }
}