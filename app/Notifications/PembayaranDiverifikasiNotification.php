<?php

namespace App\Notifications;

use App\Models\Periksa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranDiverifikasiNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Periksa $periksa
    ) {}

    /**
     * Simpan via database channel.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang disimpan ke tabel notifications.
     */
    public function toArray(object $notifiable): array
    {
        $daftarPoli = $this->periksa->daftarPoli;
        $dokter = $daftarPoli->jadwalPeriksa->dokter;
        $poli = $dokter->poli;

        return [
            'periksa_id' => $this->periksa->id,
            'daftar_poli_id' => $daftarPoli->id,
            'pesan' => 'Pembayaran Anda untuk pemeriksaan di poli ' . ($poli->nama_poli ?? '-') . ' oleh ' . ($dokter->nama ?? '-') . ' telah diverifikasi dan dinyatakan lunas.',
            'biaya' => $this->periksa->biaya_periksa,
            'tipe' => 'pembayaran_diverifikasi',
        ];
    }
}

