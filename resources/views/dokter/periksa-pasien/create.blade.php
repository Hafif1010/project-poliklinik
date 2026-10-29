<x-layouts.app title="Form Periksa Pasien">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Periksa Pasien</h2>
        <a href="{{ route('periksa-pasien.index') }}" class="btn btn-outline btn-sm rounded-lg">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 p-6 max-w-3xl">
        <form action="{{ route('periksa-pasien.store') }}" method="POST">
            @csrf

            <input type="hidden" name="id_daftar_poli" value="{{ $daftarPoli->id }}">

            <div class="space-y-4">
                {{-- Nama Pasien --}}
                <div class="form-control">
                    <label class="label font-semibold text-xs text-slate-600">Nama Pasien</label>
                    <input type="text"
                           value="{{ $daftarPoli->pasien->nama ?? '' }}"
                           class="input input-bordered w-full rounded-lg bg-slate-100 text-slate-600"
                           disabled />
                </div>

                {{-- Keluhan --}}
                <div class="form-control">
                    <label class="label font-semibold text-xs text-slate-600">Keluhan Pasien</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm text-slate-600">
                        {{ $daftarPoli->keluhan ?: 'Tidak ada keluhan.' }}
                    </div>
                </div>

                {{-- Catatan Dokter --}}
                <div class="form-control">
                    <label class="label font-semibold text-xs text-slate-600">Catatan Dokter</label>
                    <textarea name="catatan"
                              class="textarea textarea-bordered w-full rounded-lg"
                              rows="3"
                              placeholder="Masukkan diagnosis atau catatan medis...">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Pilih Obat --}}
                <div class="form-control">
                    <label class="label font-semibold text-xs text-slate-600">Obat yang Diberikan</label>
                    <select name="obat[]" class="select select-bordered w-full rounded-lg" multiple required style="min-height: 150px">
                        @foreach($obats ?? [] as $obat)
                            <option value="{{ $obat->id }}" {{ in_array($obat->id, old('obat', [])) ? 'selected' : '' }}>
                                {{ $obat->nama_obat }} ({{ $obat->kemasan }}) — Rp {{ number_format($obat->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-xs text-slate-400 mt-1">*Tahan Ctrl / Cmd untuk memilih lebih dari 1 obat</span>
                    @error('obat')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('periksa-pasien.index') }}" class="btn btn-ghost rounded-lg">Batal</a>
                <button type="submit" class="btn bg-indigo-600 hover:bg-indigo-700 text-white border-none rounded-lg px-6">
                    <i class="fas fa-save"></i> Simpan Hasil Periksa
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
