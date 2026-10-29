<x-layouts.app title="Detail Pembayaran">

    @php
        $daftar = $periksa->daftarPoli;
        $pasien = $daftar?->pasien;
        $jadwal = $daftar?->jadwalPeriksa;
        $dokter = $jadwal?->dokter;
        $poli   = $dokter?->poli;
    @endphp

    <div class="max-w-4xl mx-auto">

        {{-- Back --}}
        <div class="mb-5">
            <a href="{{ route('admin.pembayaran.index') }}"
               class="btn btn-ghost btn-sm gap-2 rounded-lg text-base-content/60 hover:text-base-content">
                <i class="fas fa-arrow-left text-xs"></i> Kembali
            </a>
        </div>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base-content">Detail Pembayaran</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Info Pasien & Pemeriksaan --}}
            <div class="space-y-5">
                <div class="card bg-base-100 shadow-xl rounded-2xl">
                    <div class="card-body p-6">
                        <h3 class="font-bold text-sm text-base-content/60 uppercase tracking-wider mb-4">
                            <i class="fas fa-user mr-2 text-primary"></i>Info Pasien
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-base-content/50">Nama Pasien</p>
                                <p class="font-semibold text-sm">{{ $pasien->nama ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/50">No. Rekam Medis</p>
                                <p class="font-semibold text-sm">{{ $pasien->no_rm ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/50">Poli / Dokter</p>
                                <p class="font-semibold text-sm">{{ $poli->nama_poli ?? '-' }} — {{ $dokter->nama ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/50">Tanggal Periksa</p>
                                <p class="font-semibold text-sm">{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daftar Obat --}}
                <div class="card bg-base-100 shadow-xl rounded-2xl">
                    <div class="card-body p-6">
                        <h3 class="font-bold text-sm text-base-content/60 uppercase tracking-wider mb-4">
                            <i class="fas fa-pills mr-2 text-warning"></i>Obat yang Diresepkan
                        </h3>
                        @if($periksa->detailPeriksas->isEmpty())
                            <p class="text-sm text-base-content/40">Tidak ada obat.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="table table-sm">
                                    <thead>
                                        <tr><th class="text-xs">Nama Obat</th><th class="text-xs">Kemasan</th><th class="text-xs text-right">Harga</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($periksa->detailPeriksas as $detail)
                                        <tr>
                                            <td class="text-sm">{{ $detail->obat->nama_obat ?? '-' }}</td>
                                            <td class="text-sm text-base-content/60">{{ $detail->obat->kemasan ?? '-' }}</td>
                                            <td class="text-sm text-right">Rp {{ number_format($detail->obat->harga ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="mt-4 pt-3 border-t border-base-300 flex justify-between items-center">
                            <span class="font-bold text-sm">Total Biaya</span>
                            <span class="font-extrabold text-lg text-primary">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bukti Pembayaran + Konfirmasi --}}
            <div class="space-y-5">
                <div class="card bg-base-100 shadow-xl rounded-2xl">
                    <div class="card-body p-6">
                        <h3 class="font-bold text-sm text-base-content/60 uppercase tracking-wider mb-4">
                            <i class="fas fa-image mr-2 text-info"></i>Bukti Pembayaran
                        </h3>

                        @if($periksa->bukti_pembayaran)
                            <img src="{{ asset('storage/' . $periksa->bukti_pembayaran) }}"
                                 alt="Bukti Pembayaran"
                                 class="w-full rounded-xl border border-base-300 mb-4 cursor-pointer"
                                 onclick="window.open(this.src, '_blank')" />
                        @else
                            <p class="text-sm text-base-content/40">Belum ada bukti pembayaran.</p>
                        @endif

                        {{-- Status --}}
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-sm font-semibold">Status:</span>
                            @if($periksa->status_pembayaran === 'lunas')
                                <span class="badge badge-success gap-1"><i class="fas fa-check-circle text-[10px]"></i> Lunas</span>
                            @elseif($periksa->status_pembayaran === 'menunggu_verifikasi')
                                <span class="badge badge-info gap-1"><i class="fas fa-hourglass-half text-[10px]"></i> Menunggu Verifikasi</span>
                            @else
                                <span class="badge badge-error gap-1"><i class="fas fa-circle-xmark text-[10px]"></i> Belum Bayar</span>
                            @endif
                        </div>

                        {{-- Tombol Konfirmasi --}}
                        @if($periksa->status_pembayaran === 'menunggu_verifikasi')
                        <form action="{{ route('admin.pembayaran.confirm', $periksa->id) }}" method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin mengkonfirmasi pembayaran ini sebagai LUNAS?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-full rounded-xl gap-2 text-white">
                                <i class="fas fa-check-double"></i> Konfirmasi Lunas
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-layouts.app>

