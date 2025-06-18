<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Dompet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nama', 'saldo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function history()
    {
        return $this->hasMany(TransaksiTabungan::class);
    }
}
