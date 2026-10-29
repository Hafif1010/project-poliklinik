<?php
// Test sending notification manually to verify it works
use App\Models\Periksa;
use App\Models\User;
use App\Notifications\PasienSudahDiperiksaNotification;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Periksa id=1, daftar_poli_id=1, pasien=6 (rizal)
$periksa = Periksa::with(['daftarPoli.jadwalPeriksa.dokter.poli', 'daftarPoli.pasien'])->find(1);

if (!$periksa) {
    echo "Periksa id=1 not found!" . PHP_EOL;
    exit(1);
}

$pasien = $periksa->daftarPoli->pasien;
echo "Sending notification to: {$pasien->nama} (id={$pasien->id})" . PHP_EOL;

try {
    $pasien->notify(new PasienSudahDiperiksaNotification($periksa));
    echo "SUCCESS! Notification sent." . PHP_EOL;
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}

// Verify
echo "Notif count for {$pasien->nama}: " . $pasien->notifications()->count() . PHP_EOL;
$latestNotif = $pasien->notifications()->latest()->first();
if ($latestNotif) {
    echo "Latest notif data: " . json_encode($latestNotif->data) . PHP_EOL;
}

