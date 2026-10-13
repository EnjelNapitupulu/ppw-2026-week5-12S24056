<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Buku>
 */
class BukuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'isbn' => fake()->unique()->numerify('978##########'),
            'judul' => fake()->unique()->randomElement([
                'Pemrograman Web dengan Laravel', 'Algoritma dan Struktur Data', 'Basis Data Lanjut',
                'Jaringan Komputer Modern', 'Kecerdasan Buatan Dasar', 'Rekayasa Perangkat Lunak',
                'Kalkulus untuk Teknik', 'Fisika Dasar I', 'Statistika Terapan', 'Manajemen Proyek TI',
                'Sistem Operasi', 'Keamanan Informasi', 'Pemrograman Python', 'Interaksi Manusia dan Komputer',
                'Analisis dan Desain Sistem', 'Matematika Diskrit', 'Cloud Computing', 'Data Mining',
                'Etika Profesi TI', 'Kewirausahaan Digital',
            ]),
            'penulis' => fake('id_ID')->name(),
            'penerbit' => fake('id_ID')->company(),
            'tahun_terbit' => fake()->numberBetween(2005, (int) date('Y')),
            'kategori_id' => Kategori::inRandomOrder()->value('id'),
            'stok' => fake()->numberBetween(0, 25),
            'sinopsis' => fake()->paragraph(3),
        ];
    }
}