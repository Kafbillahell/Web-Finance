<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tabungan;
use App\Models\User;

class TabunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        // Create sample tabungan entries for each user
        foreach ($users as $user) {
            // Education Fund
            Tabungan::create([
                'user_id' => $user->id,
                'nama' => 'Education Fund',
                'saldo' => 5000000,
                'target' => 20000000,
            ]);

            // Emergency Fund
            Tabungan::create([
                'user_id' => $user->id,
                'nama' => 'Emergency Fund',
                'saldo' => 3000000,
                'target' => 10000000,
            ]);

            // Travel Fund
            Tabungan::create([
                'user_id' => $user->id,
                'nama' => 'Travel Fund',
                'saldo' => 1500000,
                'target' => 5000000,
            ]);

            // Investment Fund
            Tabungan::create([
                'user_id' => $user->id,
                'nama' => 'Investment Fund',
                'saldo' => 2500000,
                'target' => 15000000,
            ]);

            // Retirement Fund
            Tabungan::create([
                'user_id' => $user->id,
                'nama' => 'Retirement Fund',
                'saldo' => 4000000,
                'target' => 25000000,
            ]);

            // Car Fund
            Tabungan::create([
                'user_id' => $user->id,
                'nama' => 'Car Fund',
                'saldo' => 2000000,
                'target' => 10000000,
            ]);

            // Wedding Fund
            Tabungan::create([
                'user_id' => $user->id,
                'nama' => 'Wedding Fund',
                'saldo' => 3500000,
                'target' => 15000000,
            ]);

            // House Fund
            Tabungan::create([
                'user_id' => $user->id,
                'nama' => 'House Fund',
                'saldo' => 5000000,
                'target' => 30000000,
            ]);

            // Medical Fund
            Tabungan::create([
                'user_id' => $user->id,
                'nama' => 'Medical Fund',
                'saldo' => 2500000,
                'target' => 10000000,
            ]);
        }
    }
}
