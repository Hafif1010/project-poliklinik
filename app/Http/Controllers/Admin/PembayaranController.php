<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Notifications\PembayaranDiverifikasiNotification;

class PembayaranController extends Controller
{
    /**
     * Daftar tagihan yang menunggu verifikasi.
     * Eager-load relasi agar tidak N+1 query.
     */
    public function index()
    {
        $tagihans = Periksa::with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter.poli'])
            ->where('status_pembayaran', 'menunggu_verifikasi')
            ->latest('tgl_periksa')
            ->get();

        return view('admin.pembayaran.index', compact('tagihans'));
    }

    /**
     * Detail tagihan + bukti pembayaran.
     */
    public function show($id)
    {
        $periksa = Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'detailPeriksas.obat',
        ])->findOrFail($id);

        return view('admin.pembayaran.show', compact('periksa'));
    }

    /**
     * Konfirmasi/verifikasi pembayaran menjadi lunas.
     */
    public function confirm($id)
    {
        $periksa = Periksa::with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter.poli'])->findOrFail($id);

        $periksa->update([
            'status_pembayaran' => 'lunas',
        ]);

        // Kirim notifikasi ke pasien
        $pasien = $periksa->daftarPoli->pasien;
        if ($pasien) {
            $pasien->notify(new PembayaranDiverifikasiNotification($periksa));
        }

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi sebagai lunas.');
    }
}

