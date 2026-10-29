<?php

namespace App\Http\Controllers;

use App\Exports\DokterExport;
use App\Exports\JadwalPeriksaExport;
use App\Exports\ObatExport;
use App\Exports\PasienExport;
use App\Exports\RiwayatPasienExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export Data Dokter (Admin)
     */
    public function dokter()
    {
        return Excel::download(new DokterExport, 'data-dokter-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Data Pasien (Admin)
     */
    public function pasien()
    {
        return Excel::download(new PasienExport, 'data-pasien-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Data Obat (Admin)
     */
    public function obat()
    {
        return Excel::download(new ObatExport, 'data-obat-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Jadwal Periksa dokter yang login (Dokter)
     */
    public function jadwalPeriksa()
    {
        return Excel::download(
            new JadwalPeriksaExport(Auth::id()),
            'jadwal-periksa-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Riwayat Pasien yang pernah diperiksa dokter yang login (Dokter)
     */
    public function riwayatPasien()
    {
        return Excel::download(
            new RiwayatPasienExport(Auth::id()),
            'riwayat-pasien-' . date('Y-m-d') . '.xlsx'
        );
    }
}

