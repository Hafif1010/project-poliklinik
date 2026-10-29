<x-layouts.app title="Riwayat Pendaftaran Poli">

    <div class="max-w-5xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base-content">Riwayat Pendaftaran Poli</h1>
            <p class="text-sm text-base-content/60 mt-1">Daftar semua pendaftaran poli Anda, diurutkan dari yang terbaru.</p>
        </div>

        @if($daftarPolis->isEmpty())
            {{-- Empty State --}}
            <div class="card bg-base-100 shadow-xl rounded-2xl">
                <div class="card-body items-center text-center py-16">
                    <i class="fas fa-folder-open text-5xl text-base-content/20 mb-4"></i>
                    <h3 class="text-lg font-semibold text-base-content/60">Belum Ada Riwayat</h3>
                    <p class="text-sm text-base-content/40 mt-1">Anda belum pernah melakukan pendaftaran poli.</p>
                    <a href="{{ route('pasien.daftar') }}"
                       class="btn btn-primary btn-sm mt-4 rounded-lg gap-2">
                        <i class="fas fa-plus"></i> Daftar Poli Sekarang
                    </a>
                </div>
            </div>
        @else
            {{-- Table Card --}}
            <div class="card bg-base-100 shadow-xl rounded-2xl">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr class="bg-base-200/50">
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 rounded-tl-2xl">No</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Poli</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Dokter</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Hari / Jam</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-center">No. Antrian</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-center">Status</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-center rounded-tr-2xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daftarPolis as $index => $daftar)
                                @php
                                    $jadwal = $daftar->jadwalPeriksa;
                                    $dokter = $jadwal?->dokter;
                                    $poli   = $dokter?->poli;
                                    $sudahDiperiksa = $daftar->periksas->isNotEmpty();
                                @endphp
                                <tr class="hover">
                                    {{-- No --}}
                                    <td class="font-semibold text-base-content/70">
                                        {{ $index + 1 }}
                                    </td>

                                    {{-- Poli --}}
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                                <i class="fas fa-hospital text-primary text-xs"></i>
                                            </div>
                                            <span class="font-semibold text-sm">{{ $poli->nama_poli ?? '-' }}</span>
                                        </div>
                                    </td>

                                    {{-- Dokter --}}
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-xs font-bold text-indigo-600">
                                                    {{ strtoupper(substr($dokter->nama ?? 'D', 0, 1)) }}
                                                </span>
                                            </div>
                                            <span class="text-sm">{{ $dokter->nama ?? '-' }}</span>
                                        </div>
                                    </td>

                                    {{-- Hari / Jam --}}
                                    <td>
                                        <div class="text-sm">
                                            <span class="font-semibold">{{ $jadwal->hari ?? '-' }}</span>
                                        </div>
                                        <div class="text-xs text-base-content/50">
                                            {{ $jadwal->jam_mulai ?? '' }} - {{ $jadwal->jam_selesai ?? '' }}
                                        </div>
                                    </td>

                                    {{-- No Antrian --}}
                                    <td class="text-center">
                                        <span class="badge badge-lg badge-outline font-bold">
                                            {{ $daftar->no_antrian }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        @if($sudahDiperiksa)
                                            <span class="badge badge-success gap-1 text-xs font-semibold">
                                                <i class="fas fa-check-circle text-[10px]"></i> Sudah Diperiksa
                                            </span>
                                        @else
                                            <span class="badge badge-warning gap-1 text-xs font-semibold">
                                                <i class="fas fa-clock text-[10px]"></i> Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="text-center">
                                        @if($sudahDiperiksa)
                                            <a href="{{ route('pasien.riwayat.show', $daftar->id) }}"
                                               class="btn btn-sm btn-primary rounded-lg gap-1">
                                                <i class="fas fa-eye text-xs"></i> Detail
                                            </a>
                                        @else
                                            <span class="text-xs text-base-content/30">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="mt-4 flex gap-4 text-sm text-base-content/60">
                <span>
                    <i class="fas fa-list-ol mr-1"></i>
                    Total: <strong>{{ $daftarPolis->count() }}</strong> pendaftaran
                </span>
                <span>
                    <i class="fas fa-check-circle mr-1 text-success"></i>
                    Sudah diperiksa: <strong>{{ $daftarPolis->filter(fn($d) => $d->periksas->isNotEmpty())->count() }}</strong>
                </span>
                <span>
                    <i class="fas fa-clock mr-1 text-warning"></i>
                    Menunggu: <strong>{{ $daftarPolis->filter(fn($d) => $d->periksas->isEmpty())->count() }}</strong>
                </span>
            </div>
        @endif

    </div>

</x-layouts.app>

