<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'tipe',
    ];

    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class);
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class);
    }
}
