<x-layouts.app title="Data Obat">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Data Obat</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('export.obat') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-semibold transition">
                <i class="fas fa-file-excel text-sm"></i>
                Export Excel
            </a>
            <a href="{{ route('obat.create') }}" class="btn bg-[#2d4499] text-white border-none rounded-lg px-5">
                <i class="fas fa-plus"></i> Tambah Obat
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success mb-4 rounded-xl shadow-sm">
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0 overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-slate-100 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4">Nama Obat</th>
                        <th class="px-6 py-4">Kemasan</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obats as $o)
                    <tr>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $o->nama_obat }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $o->kemasan }}</td>
                        <td class="px-6 py-4 text-slate-500">Rp {{ number_format($o->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('obat.edit', $o->id) }}" class="btn btn-sm bg-amber-500 text-white">Edit</a>
                                <form action="{{ route('obat.destroy', $o->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin hapus?')" class="btn btn-sm bg-red-500 text-white">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8 text-slate-400">Belum ada data obat</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>