<?php

namespace App\Notifications;

use App\Models\Periksa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasienSudahDiperiksaNotification extends Notification
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
        $dokter = $daftarPoli->jadwalPeriksa->dokter ?? null;
        $poli = $dokter->poli ?? null;

        return [
            'periksa_id' => $this->periksa->id,
            'daftar_poli_id' => $daftarPoli->id,
            'pesan' => 'Anda telah selesai diperiksa oleh ' . ($dokter->nama ?? 'Dokter') . ' di poli ' . ($poli->nama_poli ?? '-') . '. Silakan lakukan pembayaran.',
            'biaya' => $this->periksa->biaya_periksa,
            'tipe' => 'pasien_sudah_diperiksa',
        ];
    }
}

