<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiTabungan extends Model
{
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAWAL = 'withdrawal';
    //
    protected $table = 'tabungan_transaksi';
    protected $fillable = ['tabungan_id', 'dompet_id', 'tipe', 'kategori_id', 'nominal', 'keterangan'];

    public function tabungan()
    {
        return $this->belongsTo(Tabungan::class);
    }

    public function dompet()
    {
        return $this->belongsTo(Dompet::class);
    }
}
