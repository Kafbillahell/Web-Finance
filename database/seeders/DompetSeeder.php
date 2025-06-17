<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dompet;
use App\Models\User;

class DompetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample dompets for each user
        User::all()->each(function ($user) {
            Dompet::create([
                'user_id' => $user->id,
                'nama' => 'Dompet Utama',
                'saldo' => 1000000
            ]);
            
            Dompet::create([
                'user_id' => $user->id,
                'nama' => 'Tabungan',
                'saldo' => 500000
            ]);
        });
    }
}
