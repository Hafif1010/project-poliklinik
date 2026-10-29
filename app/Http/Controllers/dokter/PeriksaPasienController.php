<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\AntrianPoli;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use App\Notifications\PasienSudahDiperiksaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $daftarPoli = DaftarPoli::with('pasien')->findOrFail($id);
        $obats = Obat::all();

        return view('dokter.periksa-pasien.create', compact('daftarPoli', 'obats', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_poli,id',
            'catatan'        => 'nullable|string',
            'obat'           => 'required|array|min:1',
            'obat.*'         => 'exists:obat,id',
        ], [
            'obat.required' => 'Pilih minimal 1 obat.',
            'obat.min'      => 'Pilih minimal 1 obat.',
        ]);

        // Hitung biaya dari obat yang dipilih
        $obatIds = $request->obat;
        $totalHargaObat = Obat::whereIn('id', $obatIds)->sum('harga');
        $biayaPeriksa = $totalHargaObat + 150000; // biaya jasa dokter

        $periksa = Periksa::create([
            'id_daftar_poli' => $request->id_daftar_poli,
            'tgl_periksa'    => now(),
            'catatan'        => $request->catatan,
            'biaya_periksa'  => $biayaPeriksa,
        ]);

        foreach ($obatIds as $idObat) {
            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat'    => $idObat,
            ]);
        }

        // Update nomor antrian yang dipanggil di poli terkait
        $daftarPoli = DaftarPoli::with(['jadwalPeriksa.dokter.poli', 'pasien'])->find($request->id_daftar_poli);
        if ($daftarPoli) {
            $idPoli = $daftarPoli->jadwalPeriksa?->dokter?->id_poli;
            if ($idPoli) {
                AntrianPoli::updateOrCreate(
                    ['id_poli' => $idPoli],
                    ['no_antrian_dipanggil' => $daftarPoli->no_antrian]
                );
            }

            // Kirim notifikasi ke pasien bahwa sudah selesai diperiksa
            $pasien = $daftarPoli->pasien;
            if ($pasien) {
                $periksa->load('daftarPoli.jadwalPeriksa.dokter.poli');
                $pasien->notify(new PasienSudahDiperiksaNotification($periksa));
            }
        }

        return redirect()
            ->route('periksa-pasien.index')
            ->with('success', 'Pasien berhasil diperiksa! Data telah masuk ke riwayat pasien.');
    }
}