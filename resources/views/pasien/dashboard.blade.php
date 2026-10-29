<x-layouts.app title="Dashboard Pasien">

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Welcome --}}
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center">
                <i class="fas fa-hand-holding-medical text-2xl text-primary"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-base-content">
                    Halo, {{ auth()->user()->nama ?? 'Pasien' }}! 👋
                </h1>
                <p class="text-sm text-base-content/60">Selamat datang di sistem Poliklinik.</p>
            </div>
        </div>

        {{-- ============ ANTRIAN SAYA HARI INI ============ --}}
        <div class="card bg-base-100 shadow-xl rounded-2xl">
            <div class="card-body p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-lg text-base-content flex items-center gap-2">
                        <i class="fas fa-ticket text-primary"></i> Antrian Saya Hari Ini
                    </h2>
                    <span class="badge badge-ghost text-xs" id="lastUpdateSaya">-</span>
                </div>

                <div id="antrian-saya-container">
                    <div class="flex justify-center py-8">
                        <span class="loading loading-spinner loading-md text-primary"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ ANTRIAN SEMUA POLI (REAL-TIME) ============ --}}
        <div class="card bg-base-100 shadow-xl rounded-2xl">
            <div class="card-body p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-lg text-base-content flex items-center gap-2">
                        <i class="fas fa-hospital text-info"></i> Antrian Semua Poli
                    </h2>
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        <span class="text-xs text-success font-semibold">Live</span>
                        <span class="badge badge-ghost text-xs ml-2" id="lastUpdateAll">-</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-zebra" id="tabel-antrian">
                        <thead>
                            <tr class="bg-base-200/50">
                                <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Poli</th>
                                <th class="text-xs font-bold uppercase tracking-wider text-base-content/60">Dokter</th>
                                <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-center">Sedang Dipanggil</th>
                                <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-center">Total Antrian</th>
                                <th class="text-xs font-bold uppercase tracking-wider text-base-content/60 text-center">Sisa</th>
                            </tr>
                        </thead>
                        <tbody id="antrian-body">
                            <tr>
                                <td colspan="5" class="text-center py-8">
                                    <span class="loading loading-spinner loading-md text-primary"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        const POLL_INTERVAL = 5000; // 5 detik

        // ============ Fetch Antrian Semua Poli ============
        async function fetchAntrianPoli() {
            try {
                const res = await fetch('{{ route("api.antrian") }}');
                const data = await res.json();
                const tbody = document.getElementById('antrian-body');

                if (data.length === 0) {
                    tbody.innerHTML = `
                        <tr><td colspan="5" class="text-center py-8 text-base-content/40">
                            <i class="fas fa-inbox text-3xl mb-2 block"></i>Belum ada data antrian hari ini.
                        </td></tr>`;
                    return;
                }

                tbody.innerHTML = data.map(poli => `
                    <tr class="hover transition-all duration-300">
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                    <i class="fas fa-hospital text-primary text-xs"></i>
                                </div>
                                <span class="font-semibold text-sm">${poli.nama_poli}</span>
                            </div>
                        </td>
                        <td class="text-sm">${poli.dokter}</td>
                        <td class="text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary text-white text-xl font-extrabold shadow-lg shadow-primary/30 ${poli.no_dipanggil > 0 ? 'animate-pulse' : ''}">
                                ${poli.no_dipanggil > 0 ? poli.no_dipanggil : '-'}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-lg badge-outline font-bold">${poli.total_antrian}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-lg ${poli.sisa_antrian > 0 ? 'badge-warning' : 'badge-success'} font-bold">
                                ${poli.sisa_antrian}
                            </span>
                        </td>
                    </tr>
                `).join('');

                document.getElementById('lastUpdateAll').textContent = 'Update: ' + new Date().toLocaleTimeString('id-ID');
            } catch (e) {
                console.error('Gagal fetch antrian:', e);
            }
        }

        // ============ Fetch Antrian Saya ============
        async function fetchAntrianSaya() {
            try {
                const res = await fetch('{{ route("api.antrian.saya") }}');
                const data = await res.json();
                const container = document.getElementById('antrian-saya-container');

                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-6 text-base-content/40">
                            <i class="fas fa-calendar-xmark text-3xl mb-2"></i>
                            <p class="text-sm">Anda tidak memiliki antrian hari ini.</p>
                            <a href="{{ route('pasien.daftar') }}" class="btn btn-primary btn-sm mt-3 rounded-lg gap-1">
                                <i class="fas fa-plus text-xs"></i> Daftar Poli
                            </a>
                        </div>`;
                    return;
                }

                container.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-2 gap-4">` +
                    data.map(a => {
                        let statusBadge = '';
                        let cardClass = '';
                        if (a.status === 'sudah') {
                            statusBadge = '<span class="badge badge-success gap-1 text-xs"><i class="fas fa-check-circle text-[10px]"></i> Sudah Dipanggil</span>';
                            cardClass = 'border-success/30 bg-success/5';
                        } else if (a.status === 'selanjutnya') {
                            statusBadge = '<span class="badge badge-warning gap-1 text-xs animate-pulse"><i class="fas fa-bell text-[10px]"></i> Selanjutnya!</span>';
                            cardClass = 'border-warning/50 bg-warning/5 ring-2 ring-warning/30';
                        } else {
                            statusBadge = '<span class="badge badge-ghost gap-1 text-xs"><i class="fas fa-clock text-[10px]"></i> Menunggu</span>';
                            cardClass = 'border-base-300';
                        }

                        return `
                        <div class="border rounded-xl p-4 ${cardClass} transition-all duration-300">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="font-bold text-sm">${a.nama_poli}</p>
                                    <p class="text-xs text-base-content/50">${a.dokter}</p>
                                </div>
                                ${statusBadge}
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-center">
                                    <p class="text-xs text-base-content/50 mb-1">Antrian Anda</p>
                                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-primary/10 text-primary text-2xl font-extrabold">${a.no_antrian}</span>
                                </div>
                                <div class="text-2xl text-base-content/20"><i class="fas fa-arrow-right"></i></div>
                                <div class="text-center">
                                    <p class="text-xs text-base-content/50 mb-1">Sedang Dipanggil</p>
                                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-info/10 text-info text-2xl font-extrabold">${a.no_dipanggil > 0 ? a.no_dipanggil : '-'}</span>
                                </div>
                            </div>
                        </div>`;
                    }).join('') + `</div>`;

                document.getElementById('lastUpdateSaya').textContent = 'Update: ' + new Date().toLocaleTimeString('id-ID');
            } catch (e) {
                console.error('Gagal fetch antrian saya:', e);
            }
        }

        // ============ Initial load + polling ============
        fetchAntrianPoli();
        fetchAntrianSaya();
        setInterval(fetchAntrianPoli, POLL_INTERVAL);
        setInterval(fetchAntrianSaya, POLL_INTERVAL);
    </script>
    @endpush

</x-layouts.app>
