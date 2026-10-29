<x-layouts.app title="Verifikasi Pembayaran">

    <div class="max-w-5xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base-content">Verifikasi Pembayaran</h1>
            <p class="text-sm text-base-content/60 mt-1">Daftar pembayaran pasien yang menunggu verifikasi.</p>
        </div>

        @if($tagihans->isEmpty())
            <div class="card bg-base-100 shadow-xl rounded-2xl">
                <div class="card-body items-center text-center py-16">
                    <i class="fas fa-check-double text-5xl text-success/30 mb-4"></i>
                    <h3 class="text-lg font-semibold text-base-content/60">Semua Terverifikasi</h3>
                    <p class="text-sm text-base-content/40 mt-1">Tidak ada pembayaran yang menunggu verifikasi.</p>
                </div>
            </div>
        @else
            <div class="card bg-base-100 shadow-xl rounded-2xl">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr class="bg-base-200/50">
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 rounded-tl-2xl">No</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Pasien</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Poli</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Dokter</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Tgl Periksa</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-right">Biaya</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-center rounded-tr-2xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tagihans as $index => $periksa)
                                @php
                                    $daftar = $periksa->daftarPoli;
                                    $pasien = $daftar?->pasien;
                                    $jadwal = $daftar?->jadwalPeriksa;
                                    $dokter = $jadwal?->dokter;
                                    $poli   = $dokter?->poli;
                                @endphp
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">{{ $index + 1 }}</td>

                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                                <span class="text-xs font-bold text-amber-600">
                                                    {{ strtoupper(substr($pasien->nama ?? 'P', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="font-semibold text-sm">{{ $pasien->nama ?? '-' }}</span>
                                                <div class="text-xs text-base-content/50">{{ $pasien->no_rm ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-sm font-semibold">{{ $poli->nama_poli ?? '-' }}</td>
                                    <td class="text-sm">{{ $dokter->nama ?? '-' }}</td>

                                    <td class="text-sm">
                                        {{ \Carbon\Carbon::parse($periksa->tgl_periksa)->translatedFormat('d M Y') }}
                                    </td>

                                    <td class="text-sm text-right font-semibold">
                                        Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.pembayaran.show', $periksa->id) }}"
                                           class="btn btn-sm btn-primary rounded-lg gap-1">
                                            <i class="fas fa-eye text-xs"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>

</x-layouts.app>

