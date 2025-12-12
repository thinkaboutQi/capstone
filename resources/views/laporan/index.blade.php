@extends('layouts.app')

@section('title', 'Laporan Inventory')

@section('content')
<div class="page-header">
    <h2>📊 Laporan Inventory</h2>
    <p>Cetak dan unduh laporan stok berdasarkan periode</p>
</div>

<!-- Filter Card -->
<div class="card">
    <div class="card-header">
        <h3>🔍 Filter Laporan</h3>
    </div>
    
    <form action="{{ route('laporan.index') }}" method="GET" id="filterForm">
        <div class="filter-container">
            <!-- Quick Filter Buttons -->
            <div class="quick-filters">
                <button type="button" class="quick-btn {{ $periode === 'hari_ini' ? 'active' : '' }}" onclick="setQuickFilter('hari_ini')">
                    📅 Hari Ini
                </button>
                <button type="button" class="quick-btn {{ $periode === 'minggu_ini' ? 'active' : '' }}" onclick="setQuickFilter('minggu_ini')">
                    📆 Minggu Ini
                </button>
                <button type="button" class="quick-btn {{ $periode === 'bulan_ini' ? 'active' : '' }}" onclick="setQuickFilter('bulan_ini')">
                    🗓️ Bulan Ini
                </button>
                <button type="button" class="quick-btn {{ $periode === 'custom' ? 'active' : '' }}" onclick="setQuickFilter('custom')">
                    ⚙️ Custom
                </button>
            </div>

            <input type="hidden" name="periode" id="periodeInput" value="{{ $periode }}">

            <!-- Custom Date Range -->
            <div class="custom-range {{ $periode === 'custom' ? 'show' : '' }}" id="customRange">
                <div class="date-inputs">
                    <div class="input-group">
                        <label>📅 Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="date-input" {{ $periode !== 'custom' ? 'readonly' : '' }}>
                    </div>
                    <div class="arrow-separator">→</div>
                    <div class="input-group">
                        <label>📅 Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="date-input" {{ $periode !== 'custom' ? 'readonly' : '' }}>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    🔍 Tampilkan Laporan
                </button>
                <a href="{{ route('laporan.export') }}?periode={{ $periode }}&tanggal_mulai={{ $tanggalMulai }}&tanggal_akhir={{ $tanggalAkhir }}" 
                   class="btn btn-success" target="_blank">
                    📄 Export PDF
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="summary-grid">
    <div class="summary-card blue">
        <div class="summary-icon">📦</div>
        <div class="summary-content">
            <div class="summary-value">{{ $laporan->count() }}</div>
            <div class="summary-label">Total Item</div>
        </div>
    </div>
    <div class="summary-card green">
        <div class="summary-icon">📥</div>
        <div class="summary-content">
            <div class="summary-value">{{ number_format($laporan->sum('total_masuk'), 0) }}</div>
            <div class="summary-label">Total Stok Masuk</div>
        </div>
    </div>
    <div class="summary-card orange">
        <div class="summary-icon">📤</div>
        <div class="summary-content">
            <div class="summary-value">{{ number_format($laporan->sum('total_keluar'), 0) }}</div>
            <div class="summary-label">Total Stok Keluar</div>
        </div>
    </div>
    <div class="summary-card red">
        <div class="summary-icon">⚠️</div>
        <div class="summary-content">
            <div class="summary-value">{{ $laporan->filter(function($item) { return $item['stok'] <= $item['min_stok']; })->count() }}</div>
            <div class="summary-label">Stok Menipis</div>
        </div>
    </div>
</div>

<!-- Report Table -->
<div class="card">
    <div class="card-header">
        <h3>📋 Ringkasan Stok Periode: 
            <span class="period-badge">
                {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} - 
                {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
            </span>
        </h3>
    </div>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok Awal</th>
                    <th class="text-success">Masuk (+)</th>
                    <th class="text-danger">Keluar (-)</th>
                    <th>Stok Akhir</th>
                    <th>Satuan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $index => $item)
                <tr class="{{ $item['stok'] <= $item['min_stok'] ? 'row-warning' : '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge-code">{{ $item['kode'] }}</span></td>
                    <td><strong>{{ $item['nama'] }}</strong></td>
                    <td>{{ $item['kategori'] }}</td>
                    <td>{{ number_format($item['stok_awal'], 2) }}</td>
                    <td class="text-success"><strong>+{{ number_format($item['total_masuk'], 2) }}</strong></td>
                    <td class="text-danger"><strong>-{{ number_format($item['total_keluar'], 2) }}</strong></td>
                    <td><strong class="stok-akhir">{{ number_format($item['stok'], 2) }}</strong></td>
                    <td>{{ $item['satuan'] }}</td>
                    <td>
                        @if($item['stok'] > $item['min_stok'])
                            <span class="badge badge-success">✓ Aman</span>
                        @else
                            <span class="badge badge-danger">⚠️ Menipis</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <p>Tidak ada data untuk periode ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($laporan->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="5"><strong>TOTAL</strong></td>
                    <td class="text-success"><strong>+{{ number_format($laporan->sum('total_masuk'), 2) }}</strong></td>
                    <td class="text-danger"><strong>-{{ number_format($laporan->sum('total_keluar'), 2) }}</strong></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

<!-- Chart Section -->
<div class="card">
    <div class="card-header">
        <h3>📊 Grafik Pergerakan Stok</h3>
    </div>
    <div class="chart-container">
        <canvas id="stockChart"></canvas>
    </div>
</div>

@push('styles')
<style>
    .filter-container {
        padding: 10px 0;
    }

    .quick-filters {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .quick-btn {
        padding: 12px 24px;
        border: 2px solid #e0e0e0;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        font-size: 14px;
    }

    .quick-btn:hover {
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
    }

    .quick-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
    }

    .custom-range {
        display: none;
        margin-bottom: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .custom-range.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .date-inputs {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .input-group {
        flex: 1;
        min-width: 200px;
    }

    .input-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .date-input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
    }

    .date-input:focus {
        outline: none;
        border-color: #667eea;
    }

    .arrow-separator {
        font-size: 24px;
        color: #667eea;
        font-weight: bold;
        margin-top: 30px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    /* Summary Cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.3s;
    }

    .summary-card:hover {
        transform: translateY(-5px);
    }

    .summary-icon {
        font-size: 48px;
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    .summary-card.blue .summary-icon { background: #e3f2fd; }
    .summary-card.green .summary-icon { background: #e8f5e9; }
    .summary-card.orange .summary-icon { background: #fff3e0; }
    .summary-card.red .summary-icon { background: #ffebee; }

    .summary-value {
        font-size: 32px;
        font-weight: bold;
        color: #333;
    }

    .summary-label {
        font-size: 14px;
        color: #666;
    }

    /* Period Badge */
    .period-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    /* Table Styles */
    .table-responsive {
        overflow-x: auto;
    }

    .badge-code {
        background: #e0e0e0;
        padding: 4px 10px;
        border-radius: 4px;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
    }

    .text-success {
        color: #4caf50;
    }

    .text-danger {
        color: #f44336;
    }

    .text-center {
        text-align: center;
    }

    .stok-akhir {
        font-size: 16px;
        color: #667eea;
    }

    .row-warning {
        background: #fff3e0;
    }

    .total-row {
        background: #f5f5f5;
        font-weight: bold;
    }

    .total-row td {
        border-top: 3px solid #667eea;
        padding: 15px 12px;
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 64px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .empty-state p {
        color: #999;
        font-size: 16px;
    }

    /* Chart */
    .chart-container {
        padding: 30px;
        height: 400px;
    }

    @media (max-width: 768px) {
        .quick-filters {
            flex-direction: column;
        }

        .quick-btn {
            width: 100%;
        }

        .date-inputs {
            flex-direction: column;
        }

        .arrow-separator {
            transform: rotate(90deg);
            margin: 0;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    function setQuickFilter(periode) {
        document.getElementById('periodeInput').value = periode;
        
        // Show/hide custom range
        const customRange = document.getElementById('customRange');
        const dateInputs = customRange.querySelectorAll('.date-input');
        
        if (periode === 'custom') {
            customRange.classList.add('show');
            dateInputs.forEach(input => input.removeAttribute('readonly'));
        } else {
            customRange.classList.remove('show');
            dateInputs.forEach(input => input.setAttribute('readonly', true));
            
            // Auto submit untuk quick filters
            document.getElementById('filterForm').submit();
        }
    }

    // Chart
    const ctx = document.getElementById('stockChart');
    if (ctx) {
        const labels = {!! json_encode($laporan->pluck('nama')) !!};
        const stokMasuk = {!! json_encode($laporan->pluck('total_masuk')) !!};
        const stokKeluar = {!! json_encode($laporan->pluck('total_keluar')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Stok Masuk',
                        data: stokMasuk,
                        backgroundColor: 'rgba(76, 175, 80, 0.7)',
                        borderColor: 'rgba(76, 175, 80, 1)',
                        borderWidth: 2
                    },
                    {
                        label: 'Stok Keluar',
                        data: stokKeluar,
                        backgroundColor: 'rgba(244, 67, 54, 0.7)',
                        borderColor: 'rgba(244, 67, 54, 1)',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Perbandingan Stok Masuk vs Stok Keluar'
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection