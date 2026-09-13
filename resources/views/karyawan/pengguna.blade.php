@extends('layouts.karyawan')

@section('page-title', 'Data Pelanggan')

@section('content')
<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1 text-left">
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#21140b]">
                    Data Pelanggan
                </h1>
                <span class="rounded-full bg-[#fbf1e8] text-[#8c5a3c] border border-[#edd8cf] px-3 py-0.5 text-xs font-extrabold">
                    Kontak Pelanggan
                </span>
            </div>
            <p class="text-xs sm:text-sm text-[#7b6558] font-semibold leading-relaxed">
                Direktori kontak pelanggan untuk keperluan konfirmasi pesanan, update status pick-up, dan layanan operasional kasir/barista.
            </p>
        </div>
    </div>

    {{-- SEARCH BAR ROW --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-white p-3 rounded-2xl border border-[#ede6df] shadow-xs">
        <form action="{{ route('karyawan.pengguna') }}" method="GET" class="flex w-full sm:max-w-md items-center gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#8f7664]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, no. HP, NIS..." class="w-full bg-[#fdfbf9] border border-[#ede6df] text-xs font-semibold rounded-xl pl-9 pr-4 py-2.5 text-[#21140b] placeholder-[#8f7664]/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#3d2a1f]/10 focus:border-[#3d2a1f] transition-all" />
            </div>
            <button type="submit" class="bg-[#3d2a1f] hover:bg-[#21140b] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all cursor-pointer shadow-xs active:scale-95 shrink-0">
                Cari
            </button>
            @if($search)
                <a href="{{ route('karyawan.pengguna') }}" class="px-3 py-2.5 rounded-xl border border-[#ede6df] bg-white hover:bg-[#faf7f2] text-xs font-bold text-[#8f7664] transition-all" title="Reset Pencarian">
                    ✕
                </a>
            @endif
        </form>
    </div>

    {{-- MAIN TABLE CARD (CLEAN, NO DIVIDER LINES, NO STATUS COLUMN) --}}
    <div class="overflow-hidden rounded-2xl bg-white border border-[#ede6df] shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#faf7f2] text-[#8f7664] font-extrabold text-[11px] uppercase tracking-wider select-none">
                        <th class="px-5 py-4 text-center w-12">NO</th>
                        <th class="px-5 py-4">PELANGGAN</th>
                        <th class="px-5 py-4">TIPE & KELAS</th>
                        <th class="px-5 py-4">KONTAK</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelangganList as $index => $user)
                        @php
                            $isSiswa = (!empty($user->kelas) && $user->kelas !== '-');
                        @endphp
                        <tr class="hover:bg-[#faf7f2]/80 transition-colors">
                            {{-- No --}}
                            <td class="px-5 py-4 font-semibold text-[#8f7664] text-center">
                                {{ ($pelangganList->currentPage() - 1) * $pelangganList->perPage() + $loop->iteration }}
                            </td>

                            {{-- Pelanggan (Avatar Circle + Nama + ID + NIS) --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="relative shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#3d2a1f] text-xs font-bold text-[#d5c6b8] uppercase shadow-2xs select-none">
                                            {{ substr($user->nama, 0, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-sm text-[#21140b] leading-tight">{{ $user->nama }}</h4>
                                        <div class="mt-0.5 flex items-center gap-2 text-[10px] text-[#8f7664] font-semibold">
                                            <span>{{ $user->formatted_id }}</span>
                                            @if($user->nis)
                                                <span>• NIS: {{ $user->nis }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Tipe & Kelas --}}
                            <td class="px-5 py-4">
                                @if($isSiswa)
                                    <div class="space-y-0.5">
                                        <span class="inline-flex items-center gap-1 rounded-md bg-[#fbf1e8] text-[#8c5a3c] border border-[#edd8cf] px-2 py-0.5 text-[10px] font-bold">
                                            <span>🎓 Siswa</span>
                                        </span>
                                        <p class="text-[11px] font-bold text-[#21140b]">{{ $user->kelas }}</p>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-md bg-[#f5f1ed] text-[#6b584c] border border-[#e5ded7] px-2 py-0.5 text-[10px] font-bold">
                                        <span>👤 Guru / Umum</span>
                                    </span>
                                @endif
                            </td>

                            {{-- Kontak (Email & Phone / WhatsApp) --}}
                            <td class="px-5 py-4">
                                <div class="space-y-1">
                                    {{-- Email --}}
                                    @if($user->email)
                                        <div class="flex items-center gap-1.5">
                                            <a href="mailto:{{ $user->email }}?subject=Informasi%20Pesanan%20KOTE%20School%20Shop" class="inline-flex items-center gap-1 font-bold text-xs text-[#21140b] hover:text-[#8c5a3c] transition-colors" title="Kirim Email">
                                                <svg class="h-3.5 w-3.5 text-[#8f7664]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ $user->email }}</span>
                                            </a>
                                            <button type="button" onclick="copyToClipboard('{{ $user->email }}', 'Email')" class="text-[#b09a87] hover:text-[#21140b] p-0.5 rounded transition-colors cursor-pointer" title="Salin Email">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <p class="text-[11px] text-[#8f7664]">-</p>
                                    @endif

                                    {{-- WhatsApp / No. HP --}}
                                    @if($user->no_hp)
                                        @php
                                            $cleanHp = preg_replace('/[^0-9]/', '', $user->no_hp);
                                            $waNumber = str_starts_with($cleanHp, '0') ? '62' . substr($cleanHp, 1) : $cleanHp;
                                            $waMsg = urlencode("Halo kak {$user->nama}, kami dari staf KOTE School Shop ingin mengonfirmasi pesanan Anda.");
                                        @endphp
                                        <div class="flex items-center gap-1.5">
                                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsg }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800 hover:underline" title="Chat WhatsApp">
                                                <svg class="h-3.5 w-3.5 text-emerald-600 fill-current" viewBox="0 0 24 24">
                                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.288.043.088.072.19.014.305-.058.115-.087.187-.173.289l-.26.309c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.392-12.416c-5.514 0-10 4.486-10 10 0 1.761.458 3.418 1.258 4.864l-1.336 4.887 5.006-1.313c1.396.762 2.991 1.198 4.686 1.198 5.514 0 10-4.486 10-10s-4.486-10-10-10z"/>
                                                </svg>
                                                <span>{{ $user->no_hp }}</span>
                                            </a>
                                            <button type="button" onclick="copyToClipboard('{{ $user->no_hp }}', 'Nomor HP')" class="text-[#b09a87] hover:text-[#21140b] p-0.5 rounded transition-colors cursor-pointer" title="Salin Nomor HP">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <p class="text-[11px] text-[#8f7664]">-</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <p class="text-sm font-bold text-[#21140b]">Tidak ada data pelanggan yang cocok.</p>
                                    <p class="text-xs text-[#8f7664]">Coba ubah kata kunci pencarian.</p>
                                    @if($search)
                                        <a href="{{ route('karyawan.pengguna') }}" class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#faf5f0] text-[#8c5a3c] border border-[#ede6df] text-xs font-bold hover:bg-[#f3eae1] transition-all">
                                            Reset Pencarian
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($pelangganList->hasPages())
            <div class="px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#faf7f2]">
                <p class="text-xs font-semibold text-[#8f7664]">
                    Menampilkan <span class="font-bold text-[#21140b]">{{ $pelangganList->firstItem() ?? 0 }}</span> - <span class="font-bold text-[#21140b]">{{ $pelangganList->lastItem() ?? 0 }}</span> dari <span class="font-bold text-[#21140b]">{{ $pelangganList->total() }}</span> pelanggan
                </p>
                <div>
                    {{ $pelangganList->links() }}
                </div>
            </div>
        @else
            <div class="px-6 py-3 bg-[#faf7f2] flex items-center justify-between">
                <p class="text-xs font-semibold text-[#8f7664]">
                    Menampilkan <span class="font-bold text-[#21140b]">{{ $pelangganList->total() }}</span> pelanggan
                </p>
            </div>
        @endif
    </div>

</div>

{{-- TOAST & COPY HELPER JS --}}
<script>
function copyToClipboard(text, type) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(function() {
        if (typeof showToast === 'function') {
            showToast(`${type} berhasil disalin!`, 'success');
        } else {
            alert(`${type} berhasil disalin!`);
        }
    }).catch(function() {
        alert('Gagal menyalin text.');
    });
}
</script>
@endsection
