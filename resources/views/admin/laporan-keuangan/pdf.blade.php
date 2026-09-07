<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - {{ $periodeLabel }} - KOTE SCHOOL SHOP</title>
    <style>
        @page {
            margin: 15mm 12mm 15mm 12mm;
            size: a4 portrait;
        }

        * {
            box-sizing: border-box;
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            color: #27272a;
        }

        body {
            margin: 0;
            padding: 0;
            font-size: 9pt;
            line-height: 1.35;
        }

        /* Kop Laporan */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #5c3826;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .kop-table td {
            vertical-align: top;
            padding: 0;
            border: none;
        }

        .kop-title {
            font-size: 15pt;
            font-weight: bold;
            color: #18181b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .kop-subtitle {
            font-size: 9pt;
            font-weight: bold;
            color: #78350f;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .kop-address {
            font-size: 8pt;
            color: #52525b;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        .meta-table td {
            padding: 1.5px 0;
            border: none;
        }

        .meta-label {
            text-align: right;
            color: #52525b;
            padding-right: 6px;
            white-space: nowrap;
        }

        .meta-value {
            text-align: right;
            font-weight: bold;
            color: #18181b;
            white-space: nowrap;
        }

        /* Section Titles */
        .section-title {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #18181b;
            margin-top: 12px;
            margin-bottom: 5px;
            letter-spacing: 0.2px;
        }

        /* Standard Table Formatting */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8.5pt;
        }

        table.data-table th {
            background-color: #f4f4f5;
            color: #27272a;
            border: 1px solid #a1a1aa;
            padding: 5px 6px;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }

        table.data-table td {
            border: 1px solid #d4d4d8;
            padding: 4.5px 6px;
            vertical-align: middle;
            color: #27272a;
        }

        table.data-table tr.even td {
            background-color: #fafafa;
        }

        table.data-table tfoot td {
            border: 1px solid #a1a1aa;
            border-top: 2px solid #71717a;
            background-color: #f4f4f5;
            font-weight: bold;
            color: #18181b;
        }

        .text-left {
            text-align: left !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Financial Colors */
        .text-income {
            color: #166534 !important;
        }

        .text-expense {
            color: #991b1b !important;
        }

        .text-brand {
            color: #78350f !important;
        }

        /* Summary Cards Table */
        table.summary-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        table.summary-grid th {
            background-color: #f4f4f5;
            color: #3f3f46;
            border: 1px solid #a1a1aa;
            padding: 5px 6px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }

        table.summary-grid td {
            border: 1px solid #a1a1aa;
            padding: 7px 5px;
            text-align: center;
            vertical-align: middle;
            background-color: #ffffff;
        }

        .summary-val {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .summary-sub {
            font-size: 7.5pt;
            color: #52525b;
        }

        /* Rekap Box */
        .rekap-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d4d4d8;
            border-left: 3px solid #78350f;
            background-color: #fafafa;
            margin-top: 10px;
            margin-bottom: 14px;
        }

        .rekap-table td {
            padding: 7px 10px;
            vertical-align: top;
            border: none;
        }

        .rekap-text {
            width: 60%;
            font-size: 8pt;
            line-height: 1.35;
            color: #3f3f46;
        }

        .rekap-calc {
            width: 40%;
            text-align: right;
            font-size: 8.5pt;
            line-height: 1.35;
        }

        /* Signature Table */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        .signature-table td {
            border: none;
            padding: 0 10px;
            vertical-align: top;
        }

        .sign-left {
            width: 50%;
            text-align: left;
        }

        .sign-right {
            width: 50%;
            text-align: right;
        }

        .sign-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }

        .sign-space {
            height: 45px;
        }

        .sign-name {
            font-weight: bold;
            font-size: 9.5pt;
            text-decoration: underline;
            color: #18181b;
        }

        .sign-desc {
            font-size: 8pt;
            color: #52525b;
            margin-top: 2px;
        }

        /* Footer */
        .doc-footer {
            border-top: 1px solid #d4d4d8;
            padding-top: 5px;
            margin-top: 10px;
            font-size: 7.5pt;
            color: #71717a;
            width: 100%;
        }

        .doc-footer td {
            border: none;
            padding: 0;
            font-size: 7.5pt;
            color: #71717a;
        }
    </style>
</head>
<body>

    {{-- KOP LAPORAN --}}
    <table class="kop-table">
        <tr>
            <td style="width: 55%;">
                <div class="kop-title">KOTE SCHOOL SHOP</div>
                <div class="kop-subtitle">Laporan Keuangan dan Kinerja Bisnis</div>
                <div class="kop-address">Unit Usaha Praktik Siswa • SMKN 1 Kotabaru</div>
            </td>
            <td style="width: 45%;">
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">Periode Laporan:</td>
                        <td class="meta-value">{{ $periodeLabel }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Tanggal Cetak:</td>
                        <td class="meta-value">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Dicetak Oleh:</td>
                        <td class="meta-value">
                            {{ auth()->user()->nama ?? 'Administrator' }}
                            @if(auth()->user()?->email)
                                <span style="font-weight: normal; color: #52525b;">({{ auth()->user()->email }})</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="meta-label">Hak Akses:</td>
                        <td class="meta-value">
                            {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                            @if(auth()->user()?->jabatan)
                                - {{ auth()->user()->jabatan }}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- 1. RINGKASAN KEUANGAN --}}
    <div class="section-title">1. Ringkasan dan Ikhtisar Kinerja Finansial</div>
    <table class="summary-grid">
        <thead>
            <tr>
                <th style="width: 25%;">Total Pendapatan</th>
                <th style="width: 25%;">Total Pengeluaran</th>
                <th style="width: 25%;">Laba Bersih (Net Profit)</th>
                <th style="width: 25%;">Rata-Rata Transaksi (AOV)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="summary-val text-income">{{ $stats['totalPendapatan'] }}</div>
                    <div class="summary-sub">{{ $stats['totalTransaksi'] }} Transaksi Sukses</div>
                </td>
                <td>
                    <div class="summary-val text-expense">{{ $stats['totalPengeluaran'] }}</div>
                    <div class="summary-sub">{{ count($allPengeluaran) }} Item Biaya Operasional</div>
                </td>
                <td>
                    <div class="summary-val {{ $stats['labaBersihRaw'] >= 0 ? 'text-income' : 'text-expense' }}">
                        {{ $stats['labaBersih'] }}
                    </div>
                    <div class="summary-sub">Margin Laba: {{ number_format($stats['marginLaba'], 1, ',', '.') }}%</div>
                </td>
                <td>
                    <div class="summary-val text-brand">{{ $stats['aov'] }}</div>
                    <div class="summary-sub">Rata-Rata per Pesanan</div>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- 2. DISTRIBUSI PENGELUARAN PER KATEGORI --}}
    <div class="section-title">2. Distribusi Beban Pengeluaran per Kategori</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 40%;" class="text-left">Kategori Beban</th>
                <th style="width: 20%;" class="text-right">Total Nominal (Rp)</th>
                <th style="width: 15%;" class="text-right">Proporsi (%)</th>
                <th style="width: 20%;" class="text-center">Frekuensi Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @php $noKat = 1; @endphp
            @foreach($pengeluaranKategori as $kat)
            <tr class="{{ $noKat % 2 === 0 ? 'even' : '' }}">
                <td class="text-center">{{ $noKat++ }}</td>
                <td class="text-left font-bold">{{ $kat['nama'] }}</td>
                <td class="text-right font-bold">{{ $kat['formattedTotal'] }}</td>
                <td class="text-right">{{ number_format($kat['persen'], 1, ',', '.') }}%</td>
                <td class="text-center">{{ $kat['count'] }} kali</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-center font-bold">TOTAL BEBAN OPERASIONAL</td>
                <td class="text-right font-bold text-expense">{{ $stats['totalPengeluaran'] }}</td>
                <td class="text-right font-bold">100,0%</td>
                <td class="text-center font-bold">{{ count($allPengeluaran) }} kali</td>
            </tr>
        </tfoot>
    </table>

    {{-- 3. RINCIAN PENGELUARAN --}}
    <div class="section-title">3. Rincian Detail Pengeluaran Toko</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 13%;" class="text-center">Tanggal</th>
                <th style="width: 18%;" class="text-left">Kategori</th>
                <th style="width: 35%;" class="text-left">Keterangan / Keperluan</th>
                <th style="width: 12%;" class="text-center">Metode Bayar</th>
                <th style="width: 18%;" class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $noExp = 1; @endphp
            @forelse($allPengeluaran as $exp)
            <tr class="{{ $noExp % 2 === 0 ? 'even' : '' }}">
                <td class="text-center">{{ $noExp++ }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($exp->tanggal)->locale('id')->isoFormat('DD/MM/YYYY') }}</td>
                <td class="text-left font-bold">{{ $exp->kategori }}</td>
                <td class="text-left">{{ $exp->keterangan }}</td>
                <td class="text-center">{{ $exp->metode_pembayaran ?? 'Tunai' }}</td>
                <td class="text-right font-bold text-expense">- Rp {{ number_format((float) $exp->jumlah, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 10px; color: #71717a;">Tidak ada catatan transaksi pengeluaran pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if(count($allPengeluaran) > 0)
        <tfoot>
            <tr>
                <td colspan="5" class="text-center font-bold">TOTAL KESELURUHAN PENGELUARAN</td>
                <td class="text-right font-bold text-expense">- {{ $stats['totalPengeluaran'] }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- 4. RINCIAN PENJUALAN --}}
    <div class="section-title">4. Rincian Transaksi Penjualan Produk (Pesanan Selesai / Terverifikasi)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 14%;" class="text-center">Waktu Pesan</th>
                <th style="width: 10%;" class="text-center">ID Pesanan</th>
                <th style="width: 18%;" class="text-left">Pelanggan</th>
                <th style="width: 26%;" class="text-left">Item Menu / Produk</th>
                <th style="width: 10%;" class="text-center">Metode</th>
                <th style="width: 18%;" class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $noOrd = 1; @endphp
            @forelse($allPesanan as $ord)
            <tr class="{{ $noOrd % 2 === 0 ? 'even' : '' }}">
                <td class="text-center">{{ $noOrd++ }}</td>
                <td class="text-center">{{ $ord->tanggal_pesan ? $ord->tanggal_pesan->format('d/m/Y H:i') : '-' }}</td>
                <td class="text-center font-bold">#{{ $ord->id_pesanan }}</td>
                <td class="text-left">{{ $ord->user?->nama ?? 'Pelanggan' }}</td>
                <td class="text-left">{{ $ord->items_summary }}</td>
                <td class="text-center">{{ $ord->metode_pembayaran ?? 'QRIS' }}</td>
                <td class="text-right font-bold text-income">Rp {{ number_format((float) $ord->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 10px; color: #71717a;">Tidak ada catatan transaksi penjualan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if(count($allPesanan) > 0)
        <tfoot>
            <tr>
                <td colspan="6" class="text-center font-bold">TOTAL PENDAPATAN PENJUALAN</td>
                <td class="text-right font-bold text-income">{{ $stats['totalPendapatan'] }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- 5. KESIMPULAN REKAPITULASI --}}
    <table class="rekap-table">
        <tr>
            <td class="rekap-text">
                <strong style="color: #18181b;">Catatan & Kesimpulan Finansial:</strong><br>
                {{ $conclusion['message'] }}
            </td>
            <td class="rekap-calc">
                <strong style="color: #18181b;">Perhitungan Laba Bersih:</strong><br>
                <span>Pendapatan: <strong class="text-income">{{ $conclusion['revenueFormatted'] }}</strong></span><br>
                <span>Pengeluaran: <strong class="text-expense">- {{ $conclusion['expenseFormatted'] }}</strong></span><br>
                <span>Hasil Laba: <strong class="{{ $stats['labaBersihRaw'] >= 0 ? 'text-income' : 'text-expense' }}">{{ $conclusion['profitFormatted'] }}</strong></span>
            </td>
        </tr>
    </table>

    {{-- 6. TANDA TANGAN & PENGESAHAN --}}
    <table class="signature-table">
        <tr>
            <td class="sign-left">
                <div class="sign-box">
                    <div>Mengetahui / Penanggung Jawab,</div>
                    <div class="sign-space"></div>
                    <div class="sign-name">( .................................................... )</div>
                    <div class="sign-desc">Manager / Supervisor Toko</div>
                </div>
            </td>
            <td class="sign-right">
                <div class="sign-box">
                    <div>Kotabaru, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</div>
                    <div>Petugas yang Mencetak,</div>
                    <div class="sign-space"></div>
                    <div class="sign-name">{{ auth()->user()->nama ?? 'Administrator' }}</div>
                    <div class="sign-desc">
                        Akun: {{ auth()->user()->email ?? '-' }} &bull;
                        {{ ucfirst(auth()->user()->role ?? 'Admin') }}{{ auth()->user()?->jabatan ? ' ('.auth()->user()->jabatan.')' : '' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <table class="doc-footer">
        <tr>
            <td style="text-align: left;">&copy; {{ date('Y') }} KOTE SCHOOL SHOP • Dokumen Resmi Laporan Keuangan Toko</td>
            <td style="text-align: right;">Dicetak pada {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm:ss') }} WIB</td>
        </tr>
    </table>

</body>
</html>
