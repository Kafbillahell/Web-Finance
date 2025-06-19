<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class TransaksiExport implements FromCollection, WithHeadings
{
   protected $type;

    // Parameter tipe: 'all' atau 'recent'
    public function __construct($type = 'all')
    {
        $this->type = $type;
    }

    public function collection()
    {
        if ($this->type == 'recent') {
            return Transaksi::latest()->take(5)->get(); 
        } else {
            return Transaksi::all(); 
        }
    }

    public function headings(): array
    {
        return ['Tanggal', 'Kategori', 'Tipe', 'Nominal', 'Keterangan', 'Dompet'];
    }

    
}
