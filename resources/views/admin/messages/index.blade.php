@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Title and Subtitle --}}
    <div class="space-y-2">
        <h1 class="text-3xl font-extrabold tracking-tight text-[#21140b]">
            Pesan Masuk
        </h1>
        <p class="text-xs sm:text-sm text-[#7b6558] font-semibold leading-relaxed">
            Kelola pesan dan pertanyaan dari pelanggan Kote School Shop.
        </p>
    </div>

    {{-- Two Column Layout --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
        {{-- Left Column: Messages List (Span 5) --}}
        <div class="md:col-span-5 space-y-4">
            {{-- Filter and Select Dropdown --}}
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <select id="filter-dropdown" name="filter" onchange="window.location.href='{{ route('admin.messages.index') }}?filter=' + this.value + '{{ request()->filled('search') ? '&search=' . urlencode(request('search')) : '' }}'" class="w-full appearance-none bg-white border border-[#ede6df] text-xs font-bold rounded-xl pl-4 pr-10 py-3 text-[#21140b] focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/20 focus:border-[#3d2a1f] cursor-pointer">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Semua Pesan</option>
                        <option value="Saran / Masukan" {{ $filter === 'Saran / Masukan' ? 'selected' : '' }}>Saran & Masukan</option>
                        <option value="Kerja Sama" {{ $filter === 'Kerja Sama' ? 'selected' : '' }}>Kerja Sama</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#8f7664]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white border border-[#ede6df] text-[#21140b] hover:bg-[#faf7f2] transition-colors cursor-pointer shadow-sm">
                    <svg class="h-4 w-4 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </button>
            </div>

            {{-- Messages List --}}
            <div class="space-y-2.5 overflow-y-auto max-h-[600px] pr-1">
                @forelse ($messages as $msg)
                    @php
                        $isActive = $activeMessage && $activeMessage->id === $msg->id;
                    @endphp
                    <a href="{{ route('admin.messages.index', ['active_id' => $msg->id, 'filter' => $filter, 'search' => $search]) }}" class="relative block rounded-2xl border transition-all {{ $isActive ? 'border-[#e8dfd5] bg-[#fdfaf7] shadow-sm border-l-4 border-l-[#ab7a55]' : 'border-[#ede6df]/50 bg-white hover:bg-[#faf7f2]' }} p-4 text-left">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-extrabold text-[#21140b] {{ !$msg->is_read ? 'flex items-center gap-1.5' : '' }}">
                                {{ $msg->nama }}
                                @if (!$msg->is_read)
                                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-[#ab7a55]"></span>
                                @endif
                            </h4>
                            <span class="text-[10px] font-extrabold text-[#b09a87] message-time-rel" data-timestamp="{{ \Carbon\Carbon::parse($msg->created_at)->toIso8601String() }}">
                                {{ \Carbon\Carbon::parse($msg->created_at)->locale('id')->diffForHumans() }}
                            </span>
                        </div>
                        <p class="mt-1 text-[11px] font-bold text-[#ab7a55]">
                            {{ $msg->subjek === 'Saran / Masukan' ? 'Saran & Masukan' : $msg->subjek }}
                        </p>
                        <p class="mt-1 text-[10px] text-[#8a7b6e] line-clamp-1 font-semibold leading-relaxed">{{ $msg->pesan }}</p>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-[#ede6df] bg-white p-8 text-center text-xs text-[#8a7b6e] font-semibold">
                        Tidak ada pesan masuk.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Right Column: Message Detail (Span 7) --}}
        <div class="md:col-span-7">
            @if ($activeMessage)
                <div class="rounded-3xl border border-[#ede6df] bg-white shadow-sm overflow-hidden flex flex-col">
                    {{-- Header delete action --}}
                    <div class="p-5 border-b border-[#f5ece5] flex items-center justify-between bg-white">
                        <button type="button" onclick="openDeleteModal()" class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition-colors cursor-pointer border border-red-100">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    {{-- Sender and details --}}
                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="flex items-center gap-4">
                                {{-- Avatar with Initials --}}
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#f4e6da] text-base font-extrabold text-[#ab7a55] border border-[#ede6df]/50">
                                    @php
                                        $initials = '';
                                        $parts = explode(' ', $activeMessage->nama);
                                        foreach ($parts as $p) {
                                            $initials .= substr($p, 0, 1);
                                        }
                                        $initials = substr(strtoupper($initials), 0, 2);
                                    @endphp
                                    {{ $initials }}
                                </div>
                                <div class="space-y-0.5 text-left">
                                    <h4 class="text-sm font-extrabold text-[#21140b]">{{ $activeMessage->nama }}</h4>
                                    <p class="text-xs text-[#8a7b6e] font-bold">{{ $activeMessage->email }}</p>
                                </div>
                            </div>
                            <div class="text-left md:text-right space-y-2">
                                <div class="text-[11px] font-bold text-[#b09a87] flex flex-col md:items-end">
                                    <span>{{ \Carbon\Carbon::parse($activeMessage->created_at)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') . ' WIB' }}</span>
                                    <span class="text-[10px] text-[#ab7a55] font-semibold message-time-rel" data-timestamp="{{ \Carbon\Carbon::parse($activeMessage->created_at)->toIso8601String() }}">
                                        ({{ \Carbon\Carbon::parse($activeMessage->created_at)->locale('id')->diffForHumans() }})
                                    </span>
                                </div>
                                <span class="inline-block rounded-full bg-[#faf0e6] px-3 py-1 text-[10px] font-extrabold text-[#ab7a55] uppercase tracking-wider border border-[#edd8cf]/40">
                                    {{ $activeMessage->subjek === 'Saran / Masukan' ? 'SARAN & MASUKAN' : 'KERJA SAMA' }}
                                </span>
                            </div>
                        </div>

                        {{-- Message Body --}}
                        <div class="space-y-4 pt-6 border-t border-[#f5ece5]">
                            <h3 class="text-sm sm:text-base font-extrabold text-[#21140b] text-left">
                                Subjek: {{ $activeMessage->subjek === 'Saran / Masukan' ? 'Saran & Masukan' : $activeMessage->subjek }}
                            </h3>
                            <div class="text-xs sm:text-sm text-[#5a4d42] leading-relaxed whitespace-pre-wrap font-semibold text-left">
                                {{ $activeMessage->pesan }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="rounded-3xl border border-dashed border-[#ede6df] bg-white p-12 text-center space-y-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#faf5f0] text-[#ab7a55] mx-auto border border-[#ede6df]/50">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-extrabold text-[#21140b]">Tidak ada pesan terpilih</h4>
                    <p class="text-xs text-[#8a7b6e] font-semibold max-w-sm mx-auto">Pilih pesan dari daftar untuk melihat detail lengkap atau sesuaikan filter pencarian.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if ($activeMessage)
    {{-- CUSTOM MODAL FOR DELETE CONFIRMATION --}}
    <div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-[max(1rem,env(safe-area-inset-top))] pb-[max(1rem,env(safe-area-inset-bottom))] hidden" role="dialog" aria-modal="true">
        {{-- Backdrop with blur --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>

        {{-- Modal Panel --}}
        <div class="relative w-full max-w-sm rounded-3xl bg-white p-6 text-center shadow-[0_25px_60px_rgba(33,20,11,0.25)] border border-[#ede6df] transform transition-all space-y-5">
            {{-- Warning Icon --}}
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-500 border border-red-100">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            {{-- Text Content --}}
            <div class="space-y-2">
                <h3 class="text-base font-extrabold text-[#21140b]">Hapus Pesan</h3>
                <p class="text-xs text-[#8a7b6e] font-semibold leading-relaxed">
                    Apakah Anda yakin ingin menghapus pesan dari <span class="font-extrabold text-[#21140b]">{{ $activeMessage->nama }}</span>? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-white hover:bg-[#faf7f2] border border-[#ede6df] text-xs font-extrabold text-[#7b6558] py-3.5 rounded-full cursor-pointer transition-colors active:scale-[0.98]">
                    Batal
                </button>
                <form action="{{ route('admin.messages.destroy', $activeMessage) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-extrabold py-3.5 rounded-full cursor-pointer transition-colors shadow-sm shadow-red-200 active:scale-[0.98]" style="background-color: #dc2626;">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            const modal = document.getElementById('delete-modal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Real-time relative time updater in Indonesian
        function getRelativeTimeIndonesian(isoString) {
            const date = new Date(isoString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            if (seconds < 0) return 'Baru saja';
            if (seconds < 60) return 'Baru saja';
            
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return `${minutes} menit yang lalu`;
            
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return `${hours} jam yang lalu`;
            
            const days = Math.floor(hours / 24);
            if (days === 1) return 'Kemarin';
            if (days < 7) return `${days} hari yang lalu`;
            
            const weeks = Math.floor(days / 7);
            if (weeks < 4) return `${weeks} minggu yang lalu`;
            
            const months = Math.floor(days / 30);
            if (months < 12) return `${months} bulan yang lalu`;
            
            const years = Math.floor(days / 365);
            return `${years} tahun yang lalu`;
        }

        function updateRelativeTimes() {
            document.querySelectorAll('.message-time-rel').forEach(el => {
                const timestamp = el.getAttribute('data-timestamp');
                if (timestamp) {
                    const relativeText = getRelativeTimeIndonesian(timestamp);
                    // If it is in the detail panel, wrap in parentheses
                    if (el.tagName === 'SPAN' && el.classList.contains('font-semibold')) {
                        el.textContent = `(${relativeText})`;
                    } else {
                        el.textContent = relativeText;
                    }
                }
            });
        }

        // Run immediately and update every 10 seconds
        updateRelativeTimes();
        setInterval(updateRelativeTimes, 10000);
    </script>
@endif
@endsection
