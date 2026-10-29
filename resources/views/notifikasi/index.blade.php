<x-layouts.app title="Notifikasi">

    <div class="max-w-3xl mx-auto">

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-base-content">Notifikasi</h1>
                <p class="text-sm text-base-content/60 mt-1">Semua pemberitahuan untuk Anda.</p>
            </div>

            @if($notifications->whereNull('read_at')->count() > 0)
            <form action="{{ route('notifikasi.markAllAsRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm rounded-lg gap-1 text-primary">
                    <i class="fas fa-check-double text-xs"></i> Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>

        @if($notifications->isEmpty())
            {{-- Empty State --}}
            <div class="card bg-base-100 shadow-xl rounded-2xl">
                <div class="card-body items-center text-center py-16">
                    <i class="fas fa-bell-slash text-5xl text-base-content/20 mb-4"></i>
                    <h3 class="text-lg font-semibold text-base-content/60">Belum Ada Notifikasi</h3>
                    <p class="text-sm text-base-content/40 mt-1">Anda akan menerima pemberitahuan di sini.</p>
                </div>
            </div>
        @else
            <div class="space-y-3">
                @foreach($notifications as $notif)
                @php
                    $isUnread = is_null($notif->read_at);
                    $data = $notif->data;
                    $tipe = $data['tipe'] ?? '';
                @endphp
                <div class="card rounded-2xl shadow-sm transition-all duration-200
                    {{ $isUnread ? 'bg-primary/5 border-l-4 border-primary shadow-md' : 'bg-base-100 border border-base-200' }}">
                    <div class="card-body p-5">
                        <div class="flex items-start gap-4">
                            {{-- Icon --}}
                            <div class="flex-shrink-0 mt-0.5">
                                @if($tipe === 'pembayaran_diverifikasi')
                                    <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center">
                                        <i class="fas fa-circle-check text-success"></i>
                                    </div>
                                @elseif($tipe === 'bukti_pembayaran_diupload')
                                    <div class="w-10 h-10 rounded-xl bg-info/10 flex items-center justify-center">
                                        <i class="fas fa-cloud-arrow-up text-info"></i>
                                    </div>
                                @elseif($tipe === 'pasien_sudah_diperiksa')
                                    <div class="w-10 h-10 rounded-xl bg-warning/10 flex items-center justify-center">
                                        <i class="fas fa-stethoscope text-warning"></i>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-base-200 flex items-center justify-center">
                                        <i class="fas fa-bell text-base-content/50"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm leading-relaxed {{ $isUnread ? 'font-semibold text-base-content' : 'text-base-content/70' }}">
                                    {{ $data['pesan'] ?? 'Notifikasi baru.' }}
                                </p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-xs text-base-content/40">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $notif->created_at->diffForHumans() }}
                                    </span>

                                    @if($isUnread)
                                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                                        <span class="text-xs text-primary font-semibold">Baru</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Mark as Read --}}
                            @if($isUnread)
                            <div class="flex-shrink-0">
                                <form action="{{ route('notifikasi.markAsRead', $notif->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-sm btn-circle tooltip" data-tip="Tandai dibaca">
                                        <i class="fas fa-check text-xs text-primary"></i>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

    </div>

</x-layouts.app>

