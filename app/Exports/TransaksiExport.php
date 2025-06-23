<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PDF;

class TransaksiExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $type;

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

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Style untuk header
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2F75B5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Style untuk seluruh data (body)
        $sheet->getStyle('A2:' . $highestColumn . $highestRow)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        return [];
    }
    
}
