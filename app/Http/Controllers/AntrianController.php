<?php

namespace App\Http\Controllers;

use App\Models\AntrianPoli;
use App\Models\DaftarPoli;
use App\Models\Poli;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    /**
     * API endpoint: ambil data antrian semua poli (untuk polling AJAX).
     * Mengembalikan JSON agar bisa di-poll tanpa reload halaman.
     */
    public function getAntrian()
    {
        $polis = Poli::with('dokters')->get();

        $data = $polis->map(function ($poli) {
            // Nomor antrian yang sedang dipanggil
            $antrianAktif = AntrianPoli::where('id_poli', $poli->id)->first();
            $noDipanggil = $antrianAktif ? $antrianAktif->no_antrian_dipanggil : 0;

            // Total antrian hari ini di poli ini
            $today = now()->toDateString();
            $totalAntrian = DaftarPoli::whereHas('jadwalPeriksa.dokter', function ($q) use ($poli) {
                    $q->where('id_poli', $poli->id);
                })
                ->whereDate('created_at', $today)
                ->count();

            // Sisa antrian
            $sisaAntrian = max(0, $totalAntrian - $noDipanggil);

            // Nama dokter yang bertugas
            $dokterNames = $poli->dokters->pluck('nama')->implode(', ') ?: '-';

            return [
                'id_poli'           => $poli->id,
                'nama_poli'         => $poli->nama_poli,
                'dokter'            => $dokterNames,
                'no_dipanggil'      => $noDipanggil,
                'total_antrian'     => $totalAntrian,
                'sisa_antrian'      => $sisaAntrian,
            ];
        });

        return response()->json($data);
    }

    /**
     * API endpoint: ambil antrian milik pasien yang login hari ini.
     */
    public function getAntrianPasien()
    {
        $today = now()->toDateString();

        $antrianSaya = DaftarPoli::with(['jadwalPeriksa.dokter.poli'])
            ->where('id_pasien', Auth::id())
            ->whereDate('created_at', $today)
            ->get()
            ->map(function ($daftar) {
                $poli = $daftar->jadwalPeriksa?->dokter?->poli;
                $antrianAktif = $poli ? AntrianPoli::where('id_poli', $poli->id)->first() : null;
                $noDipanggil = $antrianAktif ? $antrianAktif->no_antrian_dipanggil : 0;

                return [
                    'nama_poli'     => $poli->nama_poli ?? '-',
                    'dokter'        => $daftar->jadwalPeriksa?->dokter?->nama ?? '-',
                    'no_antrian'    => $daftar->no_antrian,
                    'no_dipanggil'  => $noDipanggil,
                    'status'        => $daftar->no_antrian <= $noDipanggil ? 'sudah' : ($daftar->no_antrian == $noDipanggil + 1 ? 'selanjutnya' : 'menunggu'),
                ];
            });

        return response()->json($antrianSaya);
    }
}

