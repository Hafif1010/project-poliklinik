<?php

namespace App\Exports;

use App\Models\DaftarPoli;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RiwayatPasienExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $dokterId;

    public function __construct(int $dokterId)
    {
        $this->dokterId = $dokterId;
    }

    public function collection()
    {
        return DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli', 'periksas.detailPeriksas.obat'])
            ->whereHas('jadwalPeriksa', function ($q) {
                $q->where('id_dokter', $this->dokterId);
            })
            ->whereHas('periksas')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Pasien', 'Poli', 'Keluhan', 'Tgl Periksa', 'Catatan Dokter', 'Obat', 'Biaya (Rp)'];
    }

    public function map($daftar): array
    {
        static $no = 0;
        $no++;

        $periksa = $daftar->periksas->first();
        $obatNames = $periksa
            ? $periksa->detailPeriksas->map(fn($d) => $d->obat->nama_obat ?? '')->implode(', ')
            : '-';

        return [
            $no,
            $daftar->pasien->nama ?? '-',
            $daftar->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-',
            $daftar->keluhan ?? '-',
            $periksa ? $periksa->tgl_periksa : '-',
            $periksa ? $periksa->catatan : '-',
            $obatNames,
            $periksa ? $periksa->biaya_periksa : 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

