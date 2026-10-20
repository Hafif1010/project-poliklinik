<x-layouts.app title="Pendaftaran Poli">

    <div class="flex justify-center items-center py-4">
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 p-6 w-full max-w-xl">
            
            {{-- Judul di dalam Card --}}
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-slate-800 flex items-center justify-center gap-2">
                    🏥 Pendaftaran Poli
                </h2>
            </div>

            {{-- Alert Notifikasi --}}
            @if(session('success'))
            <div class="alert alert-success mb-4 rounded-xl shadow-sm text-sm">
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error mb-4 rounded-xl shadow-sm text-sm">
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                @csrf

                <input type="hidden" name="id_pasien" value="{{ $user->id }}">

                <div class="space-y-4">
                    {{-- Nomor Rekam Medis (Disabled/Read-only) --}}
                    <div class="form-control">
                        <label class="label font-semibold text-xs text-slate-600">Nomor Rekam Medis</label>
                        <input type="text" 
                               value="{{ $user->no_rm ?? '202603-005' }}" 
                               class="input input-bordered w-full rounded-lg bg-slate-100 text-slate-500 cursor-not-allowed text-sm" 
                               disabled />
                    </div>

                    {{-- Pilih Poli --}}
                    <div class="form-control">
                        <label class="label font-semibold text-xs text-slate-600">Pilih Poli</label>
                        <select name="id_poli" class="select select-bordered w-full rounded-lg text-sm" required>
                            <option value="" disabled selected>-- Pilih Poli --</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                            @endforeach
                        </select>
                        @error('id_poli') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Pilih Jadwal Periksa --}}
                    <div class="form-control">
                        <label class="label font-semibold text-xs text-slate-600">Pilih Jadwal Periksa</label>
                        <select name="id_jadwal" class="select select-bordered w-full rounded-lg text-sm" required>
                            <option value="" disabled selected>-- Pilih Jadwal --</option>
                            @foreach($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}">
                                    {{ $jadwal->dokter->nama ?? $jadwal->dokter->name }} - {{ $jadwal->hari }} ({{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_jadwal') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Keluhan --}}
                    <div class="form-control">
                        <label class="label font-semibold text-xs text-slate-600">Keluhan</label>
                        <textarea name="keluhan" 
                                  class="textarea textarea-bordered w-full rounded-lg text-sm" 
                                  rows="3" 
                                  placeholder="Tulis keluhan anda..."></textarea>
                        @error('keluhan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn bg-[#2563eb] hover:bg-[#1d4ed8] text-white border-none rounded-lg px-6">
                        Daftar Poli
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-layouts.app>