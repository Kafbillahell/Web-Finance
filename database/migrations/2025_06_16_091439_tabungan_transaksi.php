<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tabungan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabungan_id')->constrained()->onDelete('cascade');
            $table->foreignId('dompet_id')->nullable()->constrained('dompets')->onDelete('cascade');
            $table->enum('tipe', ['deposit', 'withdrawal']);
            $table->decimal('nominal', 18, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
