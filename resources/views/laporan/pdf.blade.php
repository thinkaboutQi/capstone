<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventory - BLUEST Coffee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #667eea;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 10px;
        }
        .header h2 {
            margin: 15px 0 0 0;
            font-size: 18px;
            color: #333;
        }
        .info-box {
            margin-bottom: 20px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 4px 0;
            font-size: 11px;
        }
        .info-box td:first-child {
            font-weight: bold;
            width: 140px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.data-table th {
            background: #667eea;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        table.data-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        table.data-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .text-success {
            color: #4caf50;
            font-weight: bold;
        }
        .text-danger {
            color: #f44336;
            font-weight: bold;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success {
            background: #e8f5e9;
            color: #4caf50;
        }
        .badge-danger {
            background: #ffebee;
            color: #f44336;
        }
        .total-row {
            background: #f0f0f0;
            font-weight: bold;
        }
        .total-row td {
            border-top: 3px solid #667eea;
            padding: 10px 8px;
        }
        .signature {
            margin-top: 60px;
            text-align: right;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
        }
        .signature-line {
            margin-top: 70px;
            border-top: 1px solid #333;
            width: 200px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>☕ BLUEST COFFEE</h1>
        <p>Ruko Sorrento Place, Jl. Ir. Sukarno No.17, Curug Sangereng</p>
        <p>Kelapa Dua, Kabupaten Tangerang, Banten 15810</p>
        <h2>LAPORAN INVENTORY</h2>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td>Periode Laporan</td>
                <td>: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Jenis Periode</td>
                <td>: {{ $periodeName ?? 'Custom' }}</td>
            </tr>
            <tr>
                <td>Tanggal Cetak</td>
                <td>: {{ \Carbon\Carbon::now()->format('d F Y H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Dicetak Oleh</td>
                <td>: {{ Auth::user()->name }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="20%">Nama Barang</th>
                <th width="12%">Kategori</th>
                <th width="10%">Stok Awal</th>
                <th width="10%">Masuk</th>
                <th width="10%">Keluar</th>
                <th width="10%">Stok Akhir</th>
                <th width="8%">Satuan</th>
                <th width="5%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item['kode'] }}</td>
                <td><strong>{{ $item['nama'] }}</strong></td>
                <td>{{ $item['kategori'] }}</td>
                <td style="text-align: right;">{{ number_format($item['stok_awal'], 2) }}</td>
                <td style="text-align: right;" class="text-success">+{{ number_format($item['total_masuk'], 2) }}</td>
                <td style="text-align: right;" class="text-danger">-{{ number_format($item['total_keluar'], 2) }}</td>
                <td style="text-align: right;"><strong>{{ number_format($item['stok'], 2) }}</strong></td>
                <td style="text-align: center;">{{ $item['satuan'] }}</td>
                <td style="text-align: center;">
                    @if($item['stok'] > $item['min_stok'])
                        <span class="badge badge-success">Aman</span>
                    @else
                        <span class="badge badge-danger">Tipis</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" style="text-align: right;"><strong>TOTAL</strong></td>
                <td style="text-align: right;" class="text-success"><strong>+{{ number_format($laporan->sum('total_masuk'), 2) }}</strong></td>
                <td style="text-align: right;" class="text-danger"><strong>-{{ number_format($laporan->sum('total_keluar'), 2) }}</strong></td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <div class="signature-box">
            <p>Tangerang, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p><strong>Mengetahui,</strong></p>
            <div class="signature-line"></div>
            <p style="margin-top: 5px;"><strong>Pemilik BLUEST Coffee</strong></p>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak otomatis oleh Sistem Manajemen Inventory BLUEST Coffee</p>
        <p>Halaman ini digenerate pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} WIB</p>
    </div>
</body>
</html>