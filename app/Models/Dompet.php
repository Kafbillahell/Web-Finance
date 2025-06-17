<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;

class Dompet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nama', 'saldo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class);
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
