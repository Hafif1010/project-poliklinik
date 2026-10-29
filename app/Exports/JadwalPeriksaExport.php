<?php

namespace App\Exports;

use App\Models\JadwalPeriksa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalPeriksaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $dokterId;

    public function __construct(int $dokterId)
    {
        $this->dokterId = $dokterId;
    }

    public function collection()
    {
        return JadwalPeriksa::with('dokter.poli')
            ->where('id_dokter', $this->dokterId)
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Hari', 'Jam Mulai', 'Jam Selesai', 'Poli'];
    }

    public function map($jadwal): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $jadwal->hari,
            $jadwal->jam_mulai,
            $jadwal->jam_selesai,
            $jadwal->dokter->poli->nama_poli ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

