<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PasienExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return User::where('role', 'pasien')->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Email', 'No KTP', 'No HP', 'No Rekam Medis', 'Alamat'];
    }

    public function map($pasien): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $pasien->nama,
            $pasien->email,
            $pasien->no_ktp ?? '-',
            $pasien->no_hp ?? '-',
            $pasien->no_rm ?? '-',
            $pasien->alamat ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

