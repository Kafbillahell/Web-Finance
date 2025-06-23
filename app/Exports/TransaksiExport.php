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
        $query = Transaksi::orderBy('created_at', 'desc');

        if ($this->type === 'recent') {
            $query->limit(5);
        }

        return $query->get()->map(function ($item) {
            return [
                'Tanggal'    => $item->created_at->format('d/m/Y H:i'),
                'Kategori'   => $item->kategori->nama ?? '-',
                'Tipe'       => $item->kategori->tipe ?? '-',
                'Nominal'    => 'Rp ' . number_format($item->nominal, 0, ',', '.'),
                'Keterangan' => $item->keterangan ?? '-',
                'Dompet'     => $item->dompet->nama ?? '-',
            ];
        });
    }
    public function headings(): array
    {
        return ['Tanggal', 'Kategori', 'Tipe', 'Nominal', 'Keterangan', 'Dompet'];
    }

    
}
