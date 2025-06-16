<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'user_id',
        'dompet_id',
        'kategori_id',
        'nominal',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dompet()
    {
        return $this->belongsTo(Dompet::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
