<?php

namespace App\Exports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ObatExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Obat::all();
    }

    public function headings(): array
    {
        return ['No', 'Nama Obat', 'Kemasan', 'Harga'];
    }

    public function map($obat): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $obat->nama_obat,
            $obat->kemasan,
            $obat->harga,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

