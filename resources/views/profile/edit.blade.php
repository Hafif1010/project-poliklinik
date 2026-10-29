<x-layouts.app title="Ganti Password">

    <div class="max-w-2xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base-content">Ganti Password</h1>
            <p class="text-sm text-base-content/60 mt-1">Perbarui password akun Anda untuk menjaga keamanan.</p>
        </div>

        {{-- Success Alert --}}
        @if (session('success'))
        <div class="alert alert-success mb-5 rounded-xl shadow-sm">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Form Card --}}
        <div class="card bg-base-100 shadow-xl rounded-2xl">
            <div class="card-body p-8">

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    {{-- Password Lama --}}
                    <div class="form-control mb-5">
                        <label class="label pb-1">
                            <span class="text-[0.82rem] font-semibold text-gray-700">Password Lama</span>
                        </label>

                        <label class="input input-bordered flex items-center gap-3 rounded-[10px] border-slate-200 bg-slate-50
                            @error('current_password') input-error border-red-400 bg-red-50/30 @enderror">
                            <i class="fas fa-lock text-slate-400 text-[0.82rem]"></i>

                            <input type="password" name="current_password" id="current_password"
                                placeholder="Masukkan password lama..."
                                class="grow bg-transparent text-slate-800 text-[0.88rem]">

                            <i class="fas fa-eye text-slate-400 text-[0.82rem] cursor-pointer" id="toggle_current"
                                onclick="togglePassword('current_password', 'toggle_current')"></i>
                        </label>

                        @error('current_password')
                        <label class="label pt-1">
                            <span class="label-text-alt text-red-500 text-[0.78rem]">
                                <i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div class="form-control mb-5">
                        <label class="label pb-1">
                            <span class="text-[0.82rem] font-semibold text-gray-700">Password Baru</span>
                        </label>

                        <label class="input input-bordered flex items-center gap-3 rounded-[10px] border-slate-200 bg-slate-50
                            @error('password') input-error border-red-400 bg-red-50/30 @enderror">
                            <i class="fas fa-key text-slate-400 text-[0.82rem]"></i>

                            <input type="password" name="password" id="new_password"
                                placeholder="Masukkan password baru (min. 8 karakter)..."
                                class="grow bg-transparent text-slate-800 text-[0.88rem]">

                            <i class="fas fa-eye text-slate-400 text-[0.82rem] cursor-pointer" id="toggle_new"
                                onclick="togglePassword('new_password', 'toggle_new')"></i>
                        </label>

                        @error('password')
                        <label class="label pt-1">
                            <span class="label-text-alt text-red-500 text-[0.78rem]">
                                <i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div class="form-control mb-8">
                        <label class="label pb-1">
                            <span class="text-[0.82rem] font-semibold text-gray-700">Konfirmasi Password Baru</span>
                        </label>

                        <label class="input input-bordered flex items-center gap-3 rounded-[10px] border-slate-200 bg-slate-50
                            @error('password_confirmation') input-error border-red-400 bg-red-50/30 @enderror">
                            <i class="fas fa-shield-halved text-slate-400 text-[0.82rem]"></i>

                            <input type="password" name="password_confirmation" id="confirm_password"
                                placeholder="Ulangi password baru..."
                                class="grow bg-transparent text-slate-800 text-[0.88rem]">

                            <i class="fas fa-eye text-slate-400 text-[0.82rem] cursor-pointer" id="toggle_confirm"
                                onclick="togglePassword('confirm_password', 'toggle_confirm')"></i>
                        </label>

                        @error('password_confirmation')
                        <label class="label pt-1">
                            <span class="label-text-alt text-red-500 text-[0.78rem]">
                                <i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="btn btn-primary w-full rounded-xl text-white font-semibold text-sm gap-2">
                        <i class="fas fa-floppy-disk"></i>
                        Simpan Password Baru
                    </button>

                </form>

            </div>
        </div>

        {{-- Info Card --}}
        <div class="mt-5 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-start gap-3">
                <i class="fas fa-circle-info text-blue-500 mt-0.5"></i>
                <div class="text-sm text-blue-700">
                    <p class="font-semibold mb-1">Tips keamanan password:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-blue-600">
                        <li>Gunakan minimal 8 karakter</li>
                        <li>Kombinasikan huruf besar, huruf kecil, dan angka</li>
                        <li>Jangan gunakan password yang sama dengan akun lain</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
    @endpush

</x-layouts.app>

