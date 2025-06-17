<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Income Categories
        Kategori::create([
            'nama' => 'Gaji',
            'tipe' => 'pemasukan'
        ]);

        Kategori::create([
            'nama' => 'Bonus',
            'tipe' => 'pemasukan'
        ]);

        Kategori::create([
            'nama' => 'Investasi',
            'tipe' => 'pemasukan'
        ]);

        // Expense Categories
        Kategori::create([
            'nama' => 'Makan',
            'tipe' => 'pengeluaran'
        ]);

        Kategori::create([
            'nama' => 'Transportasi',
            'tipe' => 'pengeluaran'
        ]);

        Kategori::create([
            'nama' => 'Tagihan',
            'tipe' => 'pengeluaran'
        ]);

        Kategori::create([
            'nama' => 'Belanja',
            'tipe' => 'pengeluaran'
        ]);
    }
}
