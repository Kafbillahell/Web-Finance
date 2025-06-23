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
        'id_user',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'kategori_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
