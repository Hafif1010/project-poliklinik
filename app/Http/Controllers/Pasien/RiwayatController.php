<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Tampilkan semua riwayat pendaftaran poli milik pasien yang login.
     * Eager-load relasi untuk menghindari N+1 query.
     */
    public function index()
    {
        $daftarPolis = DaftarPoli::with([
                'jadwalPeriksa.dokter.poli',  // jadwal → dokter → poli (1 query each)
                'periksas',                    // cek status sudah diperiksa atau belum
            ])
            ->where('id_pasien', Auth::id())
            ->latest()                         // urut dari terbaru
            ->get();

        return view('pasien.riwayat.index', compact('daftarPolis'));
    }

    /**
     * Tampilkan detail hasil pemeriksaan.
     * Eager-load obat via detailPeriksas untuk menghindari N+1 query.
     */
    public function show($id)
    {
        // Ambil data periksa beserta relasi, pastikan milik pasien yang login
        $periksa = Periksa::with(['detailPeriksas.obat', 'daftarPoli.jadwalPeriksa.dokter.poli'])
            ->whereHas('daftarPoli', function ($query) {
                $query->where('id_pasien', Auth::id());
            })
            ->where('id_daftar_poli', $id)
            ->firstOrFail();

        $daftarPoli = $periksa->daftarPoli;

        return view('pasien.riwayat.show', compact('periksa', 'daftarPoli'));
    }
}

