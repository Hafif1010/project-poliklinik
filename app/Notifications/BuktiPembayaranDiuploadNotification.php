<?php

namespace App\Notifications;

use App\Models\Periksa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BuktiPembayaranDiuploadNotification extends Notification
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
        $pasien = $daftarPoli->pasien;

        return [
            'periksa_id' => $this->periksa->id,
            'daftar_poli_id' => $daftarPoli->id,
            'pesan' => 'Pasien ' . ($pasien->nama ?? '-') . ' telah mengupload bukti pembayaran untuk pemeriksaan tanggal ' . $this->periksa->tgl_periksa . '.',
            'tipe' => 'bukti_pembayaran_diupload',
        ];
    }
}

