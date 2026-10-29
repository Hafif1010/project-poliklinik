<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DokterExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return User::where('role', 'dokter')->with('poli')->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Email', 'No HP', 'Poli'];
    }

    public function map($dokter): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $dokter->nama,
            $dokter->email,
            $dokter->no_hp ?? '-',
            $dokter->poli->nama_poli ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

