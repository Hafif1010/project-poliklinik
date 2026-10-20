<x-layouts.app title="Periksa Pasien">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Daftar Periksa Pasien</h2>
    </div>

    @if(session('success'))
    <div class="alert alert-success mb-4 rounded-xl shadow-sm">
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0 overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Pasien</th>
                        <th class="px-6 py-4">Keluhan</th>
                        <th class="px-6 py-4">No Antrian</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarPasien ?? [] as $index => $item)
                    <tr>
                        <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $item->pasien->nama ?? $item->pasien->name ?? 'Pasien' }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->keluhan ?? '-' }}</td>
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $item->no_antrian ?? ($index + 1) }}</td>
                        <td class="px-6 py-4 text-right">
                            @if(isset($item->periksas) && $item->periksas->isNotEmpty())
                                <span class="badge badge-success text-white py-3 px-4 font-semibold text-xs rounded-full">
                                    Sudah Diperiksa
                                </span>
                            @else
                                <a href="{{ route('periksa-pasien.create', $item->id) }}" 
                                   class="btn btn-sm bg-amber-500 hover:bg-amber-600 text-white border-none rounded-lg px-4">
                                    <i class="fas fa-stethoscope text-xs"></i> Periksa
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400">Belum ada daftar pasien yang mendaftar</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>