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

            <input type="hidden" name="id_daftar_poli" value="{{ $daftarPoli->id ?? '' }}">

            <div class="space-y-4">
                {{-- Nama Pasien --}}
                <div class="form-control">
                    <label class="label font-semibold text-xs text-slate-600">Nama Pasien</label>
                    <input type="text" 
                           value="{{ $daftarPoli->pasien->nama ?? $daftarPoli->pasien->name ?? '' }}" 
                           class="input input-bordered w-full rounded-lg bg-slate-100 text-slate-600" 
                           disabled />
                </div>

                {{-- Tgl Periksa --}}
                <div class="form-control">
                    <label class="label font-semibold text-xs text-slate-600">Tanggal Periksa</label>
                    <input type="datetime-local" 
                           name="tgl_periksa" 
                           value="{{ date('Y-m-d\TH:i') }}" 
                           class="input input-bordered w-full rounded-lg" 
                           required />
                </div>

                {{-- Catatan --}}
                <div class="form-control">
                    <label class="label font-semibold text-xs text-slate-600">Catatan Dokter</label>
                    <textarea name="catatan" 
                              class="textarea textarea-bordered w-full rounded-lg" 
                              rows="3" 
                              placeholder="Masukkan resep atau catatan medis..." 
                              required></textarea>
                </div>

                {{-- Pilih Obat --}}
                <div class="form-control">
                    <label class="label font-semibold text-xs text-slate-600">Obat yang Diberikan</label>
                    <select name="obat[]" class="select select-bordered w-full rounded-lg" multiple required>
                        @foreach($obats ?? [] as $obat)
                            <option value="{{ $obat->id }}">
                                {{ $obat->nama_obat }} - Rp {{ number_format($obat->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-xs text-slate-400 mt-1">*Tahan tombol Ctrl / Cmd untuk memilih lebih dari 1 obat</span>
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