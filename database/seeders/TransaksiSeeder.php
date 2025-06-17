<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\Dompet;
use App\Models\Kategori;
use App\Models\User;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample transactions for each user
        User::all()->each(function ($user) {
            // Get the user's dompets
            $dompets = Dompet::where('user_id', $user->id)->get();
            
            // Create income categories
            $incomeCategories = Kategori::where('tipe', 'pemasukan')->get();
            // Create expense categories
            $expenseCategories = Kategori::where('tipe', 'pengeluaran')->get();
            
            // Skip if no dompets or categories available
            if ($dompets->isEmpty() || $incomeCategories->isEmpty() || $expenseCategories->isEmpty()) {
                return;
            }

            // Create sample transactions
            $transactions = [
                // Income
                [
                    'user_id' => $user->id,
                    'dompet_id' => $dompets->first()->id,
                    'kategori_id' => $incomeCategories->random()->id,
                    'nominal' => 500000,
                    'keterangan' => 'Gaji bulanan',
                    'created_at' => Carbon::now()->subDays(5)
                ],
                [
                    'user_id' => $user->id,
                    'dompet_id' => $dompets->first()->id,
                    'kategori_id' => $incomeCategories->random()->id,
                    'nominal' => 250000,
                    'keterangan' => 'Bonus proyek',
                    'created_at' => Carbon::now()->subDays(3)
                ],
                // Expenses
                [
                    'user_id' => $user->id,
                    'dompet_id' => $dompets->first()->id,
                    'kategori_id' => $expenseCategories->random()->id,
                    'nominal' => 150000,
                    'keterangan' => 'Makan siang',
                    'created_at' => Carbon::now()->subDays(4)
                ],
                [
                    'user_id' => $user->id,
                    'dompet_id' => $dompets->first()->id,
                    'kategori_id' => $expenseCategories->random()->id,
                    'nominal' => 300000,
                    'keterangan' => 'Pembayaran tagihan',
                    'created_at' => Carbon::now()->subDays(2)
                ]
            ];

            foreach ($transactions as $transaction) {
                Transaksi::create($transaction);
            }
        });
    }
}
