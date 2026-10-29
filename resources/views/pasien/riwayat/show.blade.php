<x-layouts.app title="Detail Pemeriksaan">

    <div class="max-w-4xl mx-auto">

        {{-- Back Button --}}
        <div class="mb-5">
            <a href="{{ route('pasien.riwayat.index') }}"
               class="btn btn-ghost btn-sm gap-2 rounded-lg text-base-content/60 hover:text-base-content">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Riwayat
            </a>
        </div>

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base-content">Detail Pemeriksaan</h1>
            <p class="text-sm text-base-content/60 mt-1">Hasil pemeriksaan dan resep obat dari dokter.</p>
        </div>

        @php
            $jadwal = $daftarPoli->jadwalPeriksa;
            $dokter = $jadwal?->dokter;
            $poli   = $dokter?->poli;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ==================== KOLOM KIRI: INFO PENDAFTARAN ==================== --}}
            <div class="lg:col-span-1 space-y-5">

                {{-- Card Info Pendaftaran --}}
                <div class="card bg-base-100 shadow-xl rounded-2xl">
                    <div class="card-body p-6">
                        <h3 class="font-bold text-sm text-base-content/60 uppercase tracking-wider mb-4">
                            <i class="fas fa-clipboard-list mr-2 text-primary"></i>Info Pendaftaran
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-base-content/50">Poli</p>
                                <p class="font-semibold text-sm">{{ $poli->nama_poli ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/50">Dokter</p>
                                <p class="font-semibold text-sm">{{ $dokter->nama ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/50">Jadwal</p>
                                <p class="font-semibold text-sm">{{ $jadwal->hari ?? '-' }}, {{ $jadwal->jam_mulai ?? '' }} - {{ $jadwal->jam_selesai ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/50">No. Antrian</p>
                                <span class="badge badge-primary badge-lg font-bold">{{ $daftarPoli->no_antrian }}</span>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/50">Keluhan</p>
                                <p class="text-sm">{{ $daftarPoli->keluhan ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Tanggal Periksa --}}
                <div class="card bg-primary/5 border border-primary/20 rounded-2xl">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <i class="fas fa-calendar-check text-primary"></i>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/50">Tanggal Periksa</p>
                                <p class="font-bold text-sm">
                                    {{ \Carbon\Carbon::parse($periksa->tgl_periksa)->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== KOLOM KANAN: CATATAN & OBAT ==================== --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Catatan Dokter --}}
                <div class="card bg-base-100 shadow-xl rounded-2xl">
                    <div class="card-body p-6">
                        <h3 class="font-bold text-sm text-base-content/60 uppercase tracking-wider mb-4">
                            <i class="fas fa-stethoscope mr-2 text-success"></i>Catatan Dokter
                        </h3>

                        <div class="bg-base-200/50 rounded-xl p-4 border border-base-300">
                            <p class="text-sm leading-relaxed">
                                {{ $periksa->catatan ?: 'Tidak ada catatan.' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Daftar Obat --}}
                <div class="card bg-base-100 shadow-xl rounded-2xl">
                    <div class="card-body p-6">
                        <h3 class="font-bold text-sm text-base-content/60 uppercase tracking-wider mb-4">
                            <i class="fas fa-pills mr-2 text-warning"></i>Daftar Obat
                        </h3>

                        @if($periksa->detailPeriksas->isEmpty())
                            <div class="text-center py-6 text-base-content/40">
                                <i class="fas fa-prescription-bottle text-3xl mb-2"></i>
                                <p class="text-sm">Tidak ada obat yang diresepkan.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <thead>
                                        <tr class="bg-base-200/50">
                                            <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">No</th>
                                            <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Nama Obat</th>
                                            <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Kemasan</th>
                                            <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-right">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($periksa->detailPeriksas as $index => $detail)
                                        <tr class="hover">
                                            <td class="font-semibold text-base-content/70">{{ $index + 1 }}</td>
                                            <td class="font-semibold text-sm">{{ $detail->obat->nama_obat ?? '-' }}</td>
                                            <td class="text-sm text-base-content/70">{{ $detail->obat->kemasan ?? '-' }}</td>
                                            <td class="text-sm text-right">Rp {{ number_format($detail->obat->harga ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Total Biaya --}}
                <div class="card bg-gradient-to-r from-primary to-indigo-600 text-white rounded-2xl shadow-xl">
                    <div class="card-body p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-white/70">Total Biaya Pemeriksaan</p>
                                <p class="text-3xl font-extrabold mt-1">
                                    Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center">
                                <i class="fas fa-receipt text-2xl"></i>
                            </div>
                        </div>

                        @if($periksa->detailPeriksas->isNotEmpty())
                        <div class="mt-3 pt-3 border-t border-white/20 text-xs text-white/60">
                            <span>Termasuk {{ $periksa->detailPeriksas->count() }} jenis obat</span>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-layouts.app>

