<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Laporan Keuangan - KOTE SCHOOL SHOP</title>
    <style>
        body, table, td, th {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #000000;
        }
        .title {
            font-size: 14pt;
            font-weight: bold;
        }
        .subtitle {
            font-size: 11pt;
            font-weight: bold;
            color: #555555;
        }
        .meta {
            font-size: 9pt;
            color: #333333;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            background-color: #e8ded5;
            padding: 6px 8px;
            border: 1px solid #c4b5a5;
        }
        .table-header th {
            background-color: #3d2a1f;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #241812;
        }
        .cell {
            padding: 5px 8px;
            border: 1px solid #dcdcdc;
            vertical-align: middle;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row td {
            background-color: #f7f3ef;
            font-weight: bold;
            border: 1px solid #c4b5a5;
            padding: 6px 8px;
        }
    </style>
</head>
<body>
    <table border="0" cellpadding="0" cellspacing="0">
        {{-- JUDUL TOKO --}}
        <tr>
            <td colspan="7" class="title">KOTE SCHOOL SHOP</td>
        </tr>
        <tr>
            <td colspan="7" class="subtitle">LAPORAN KEUANGAN</td>
        </tr>
        <tr>
            <td colspan="7" class="meta">Periode: {{ $periodeLabel }} ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})</td>
        </tr>
        <tr>
            <td colspan="7" class="meta">Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB</td>
        </tr>
        <tr><td colspan="7" style="height: 12px;"></td></tr>

        {{-- RINGKASAN SEDERHANA --}}
        <tr>
            <td colspan="7" class="section-title">RINGKASAN</td>
        </tr>
        <tr>
            <td colspan="2" class="cell" style="font-weight: bold; background-color: #faf7f5;">Total Pendapatan</td>
            <td colspan="5" class="cell text-right" style="font-weight: bold; color: #059669;">Rp {{ number_format($stats['totalPendapatanRaw'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2" class="cell" style="font-weight: bold; background-color: #faf7f5;">Total Pengeluaran</td>
            <td colspan="5" class="cell text-right" style="font-weight: bold; color: #dc2626;">Rp {{ number_format($stats['totalPengeluaranRaw'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2" class="cell" style="font-weight: bold; background-color: #ede5dc;">Laba Bersih</td>
            <td colspan="5" class="cell text-right" style="font-weight: bold; color: #059669; font-size: 11pt; background-color: #ede5dc;">Rp {{ number_format($stats['labaBersihRaw'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2" class="cell" style="background-color: #faf7f5;">Total Transaksi Penjualan</td>
            <td colspan="5" class="cell text-right">{{ $stats['totalTransaksi'] }} Transaksi</td>
        </tr>
        <tr><td colspan="7" style="height: 14px;"></td></tr>

        {{-- TABEL PENJUALAN --}}
        <tr>
            <td colspan="7" class="section-title">RINCIAN PENJUALAN</td>
        </tr>
        <tr class="table-header">
            <th style="width: 35px;" class="text-center">No</th>
            <th style="width: 170px;">Tanggal & Waktu</th>
            <th style="width: 100px;">No. Pesanan</th>
            <th style="width: 140px;">Pelanggan</th>
            <th style="width: 220px;">Menu / Item</th>
            <th style="width: 90px;">Metode</th>
            <th style="width: 130px;" class="text-right">Total (Rp)</th>
        </tr>
        @php $noP = 1; @endphp
        @forelse($allPesanan as $ord)
        <tr>
            <td class="cell text-center">{{ $noP++ }}</td>
            <td class="cell" style="mso-number-format:'\@';">{{ $ord->tanggal_pesan ? $ord->tanggal_pesan->format('d/m/Y H:i') : '-' }}</td>
            <td class="cell">#{{ $ord->id_pesanan }}</td>
            <td class="cell">{{ $ord->user?->nama ?? 'Pelanggan Umum' }}</td>
            <td class="cell">{{ $ord->items_summary }}</td>
            <td class="cell">{{ $ord->metode_pembayaran ?? ($ord->pembayaran?->metode ?? 'Tunai') }}</td>
            <td class="cell text-right">Rp {{ number_format((float) $ord->total_harga, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="cell text-center" style="color: #777777;">Tidak ada transaksi penjualan pada periode ini.</td>
        </tr>
        @endforelse
        <tr class="total-row">
            <td colspan="6" class="text-center">TOTAL PENDAPATAN</td>
            <td class="text-right" style="color: #059669;">Rp {{ number_format($stats['totalPendapatanRaw'], 0, ',', '.') }}</td>
        </tr>
        <tr><td colspan="7" style="height: 14px;"></td></tr>

        {{-- TABEL PENGELUARAN --}}
        <tr>
            <td colspan="7" class="section-title">RINCIAN PENGELUARAN</td>
        </tr>
        <tr class="table-header">
            <th style="width: 35px;" class="text-center">No</th>
            <th style="width: 120px;">Tanggal</th>
            <th style="width: 140px;">Kategori</th>
            <th colspan="2" style="width: 310px;">Keterangan</th>
            <th style="width: 90px;">Metode</th>
            <th style="width: 130px;" class="text-right">Jumlah (Rp)</th>
        </tr>
        @php $noE = 1; @endphp
        @forelse($allPengeluaran as $exp)
        <tr>
            <td class="cell text-center">{{ $noE++ }}</td>
            <td class="cell" style="mso-number-format:'\@';">{{ \Carbon\Carbon::parse($exp->tanggal)->format('d/m/Y') }}</td>
            <td class="cell">{{ $exp->kategori }}</td>
            <td colspan="2" class="cell">{{ $exp->keterangan }}</td>
            <td class="cell">{{ $exp->metode_pembayaran ?? 'Tunai' }}</td>
            <td class="cell text-right">Rp {{ number_format((float) $exp->jumlah, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="cell text-center" style="color: #777777;">Tidak ada catatan pengeluaran pada periode ini.</td>
        </tr>
        @endforelse
        <tr class="total-row">
            <td colspan="6" class="text-center">TOTAL PENGELUARAN</td>
            <td class="text-right" style="color: #dc2626;">Rp {{ number_format($stats['totalPengeluaranRaw'], 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>
