<x-layouts.app title="Pembayaran">

    <div class="max-w-5xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base-content">Pembayaran</h1>
            <p class="text-sm text-base-content/60 mt-1">Daftar tagihan dari hasil pemeriksaan Anda.</p>
        </div>

        @if($tagihans->isEmpty())
            <div class="card bg-base-100 shadow-xl rounded-2xl">
                <div class="card-body items-center text-center py-16">
                    <i class="fas fa-receipt text-5xl text-base-content/20 mb-4"></i>
                    <h3 class="text-lg font-semibold text-base-content/60">Belum Ada Tagihan</h3>
                    <p class="text-sm text-base-content/40 mt-1">Anda belum memiliki tagihan pemeriksaan.</p>
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
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Poli</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Dokter</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Tgl Periksa</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-right">Biaya</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-center">Status</th>
                                    <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-center rounded-tr-2xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tagihans as $index => $periksa)
                                @php
                                    $daftar = $periksa->daftarPoli;
                                    $jadwal = $daftar?->jadwalPeriksa;
                                    $dokter = $jadwal?->dokter;
                                    $poli   = $dokter?->poli;
                                @endphp
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">{{ $index + 1 }}</td>

                                    <td>
                                        <span class="font-semibold text-sm">{{ $poli->nama_poli ?? '-' }}</span>
                                    </td>

                                    <td class="text-sm">{{ $dokter->nama ?? '-' }}</td>

                                    <td class="text-sm">
                                        {{ \Carbon\Carbon::parse($periksa->tgl_periksa)->translatedFormat('d M Y') }}
                                    </td>

                                    <td class="text-sm text-right font-semibold">
                                        Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        @if($periksa->status_pembayaran === 'lunas')
                                            <span class="badge badge-success gap-1 text-xs font-semibold">
                                                <i class="fas fa-check-circle text-[10px]"></i> Lunas
                                            </span>
                                        @elseif($periksa->status_pembayaran === 'menunggu_verifikasi')
                                            <span class="badge badge-info gap-1 text-xs font-semibold">
                                                <i class="fas fa-hourglass-half text-[10px]"></i> Menunggu Verifikasi
                                            </span>
                                        @else
                                            <span class="badge badge-error gap-1 text-xs font-semibold">
                                                <i class="fas fa-circle-xmark text-[10px]"></i> Belum Bayar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if($periksa->status_pembayaran === 'belum_bayar')
                                            <button onclick="document.getElementById('modal-{{ $periksa->id }}').showModal()"
                                                class="btn btn-sm btn-primary rounded-lg gap-1">
                                                <i class="fas fa-upload text-xs"></i> Upload Bukti
                                            </button>
                                        @elseif($periksa->status_pembayaran === 'menunggu_verifikasi')
                                            <button onclick="document.getElementById('preview-{{ $periksa->id }}').showModal()"
                                                class="btn btn-sm btn-ghost rounded-lg gap-1">
                                                <i class="fas fa-eye text-xs"></i> Lihat Bukti
                                            </button>
                                        @elseif($periksa->status_pembayaran === 'lunas')
                                            <span class="text-xs text-success font-semibold"><i class="fas fa-circle-check"></i></span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Modal Upload --}}
                                @if($periksa->status_pembayaran === 'belum_bayar')
                                <dialog id="modal-{{ $periksa->id }}" class="modal">
                                    <div class="modal-box rounded-2xl">
                                        <form method="dialog">
                                            <button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3">✕</button>
                                        </form>

                                        <h3 class="font-bold text-lg mb-1">Upload Bukti Pembayaran</h3>
                                        <p class="text-sm text-base-content/60 mb-4">
                                            {{ $poli->nama_poli ?? '-' }} — Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                                        </p>

                                        <form action="{{ route('pasien.pembayaran.upload', $periksa->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="form-control mb-4">
                                                <label class="label">
                                                    <span class="label-text font-semibold text-sm">Foto Bukti Pembayaran</span>
                                                </label>
                                                <input type="file" name="bukti_pembayaran" accept="image/jpeg,image/png,image/jpg"
                                                    class="file-input file-input-bordered w-full rounded-lg" required />
                                                <label class="label">
                                                    <span class="label-text-alt text-base-content/50">Format: JPG, JPEG, PNG. Maks: 2MB</span>
                                                </label>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-full rounded-xl gap-2">
                                                <i class="fas fa-cloud-arrow-up"></i> Upload
                                            </button>
                                        </form>
                                    </div>
                                    <form method="dialog" class="modal-backdrop"><button>close</button></form>
                                </dialog>
                                @endif

                                {{-- Modal Preview Bukti --}}
                                @if($periksa->bukti_pembayaran)
                                <dialog id="preview-{{ $periksa->id }}" class="modal">
                                    <div class="modal-box rounded-2xl max-w-lg">
                                        <form method="dialog">
                                            <button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3">✕</button>
                                        </form>

                                        <h3 class="font-bold text-lg mb-4">Bukti Pembayaran</h3>
                                        <img src="{{ asset('storage/' . $periksa->bukti_pembayaran) }}"
                                             alt="Bukti Pembayaran"
                                             class="w-full rounded-xl border border-base-300" />
                                    </div>
                                    <form method="dialog" class="modal-backdrop"><button>close</button></form>
                                </dialog>
                                @endif

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>

</x-layouts.app>

