<x-layouts.app title="Tambah Obat">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Tambah Obat</h2>
        <a href="{{ route('obat.index') }}" class="btn bg-slate-500 text-white">Kembali</a>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2 border p-6">
        <form action="{{ route('obat.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="label font-semibold">Nama Obat</label>
                    <input type="text" name="nama_obat" value="{{ old('nama_obat') }}" class="input input-bordered w-full" />
                    @error('nama_obat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="label font-semibold">Kemasan</label>
                    <input type="text" name="kemasan" value="{{ old('kemasan') }}" class="input input-bordered w-full" placeholder="cth: Botol / Tablet" />
                    @error('kemasan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="label font-semibold">Harga</label>
                    <input type="number" name="harga" value="{{ old('harga') }}" class="input input-bordered w-full" />
                    @error('harga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="btn bg-[#2d4499] text-white mt-6">Simpan</button>
        </form>
    </div>
</x-layouts.app>