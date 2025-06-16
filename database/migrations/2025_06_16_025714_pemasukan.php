<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('dompet_id')->nullable()->constrained('dompets')->onDelete('cascade');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->onDelete('cascade');
            $table->decimal('nominal', 18, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pemasukan');
    }
};
