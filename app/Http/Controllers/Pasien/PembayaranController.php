<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Notifications\BuktiPembayaranDiuploadNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Daftar tagihan pasien yang login.
     * Eager-load untuk menghindari N+1 query.
     */
    public function index()
    {
        $tagihans = Periksa::with(['daftarPoli.jadwalPeriksa.dokter.poli'])
            ->whereHas('daftarPoli', function ($q) {
                $q->where('id_pasien', Auth::id());
            })
            ->latest('tgl_periksa')
            ->get();

        return view('pasien.pembayaran.index', compact('tagihans'));
    }

    /**
     * Upload bukti pembayaran.
     */
    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_pembayaran.image'    => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes'    => 'Format gambar harus JPG, JPEG, atau PNG.',
            'bukti_pembayaran.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Pastikan periksa ini milik pasien yang login
        $periksa = Periksa::whereHas('daftarPoli', function ($q) {
            $q->where('id_pasien', Auth::id());
        })->findOrFail($id);

        // Simpan file
        $path = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

        $periksa->update([
            'bukti_pembayaran'  => $path,
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        // Kirim notifikasi ke dokter yang memeriksa
        $periksa->load('daftarPoli.jadwalPeriksa.dokter', 'daftarPoli.pasien');
        $dokter = $periksa->daftarPoli->jadwalPeriksa->dokter ?? null;
        if ($dokter) {
            $dokter->notify(new BuktiPembayaranDiuploadNotification($periksa));
        }

        return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }
}

