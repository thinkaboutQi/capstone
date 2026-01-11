@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h2>Dashboard</h2>
    <p>Selamat datang di sistem manajemen inventory BLUEAST Coffee</p>
</div>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon.blue { background: #e3f2fd; color: #2196f3; }
    .stat-icon.green { background: #e8f5e9; color: #4caf50; }
    .stat-icon.orange { background: #fff3e0; color: #ff9800; }
    .stat-icon.red { background: #ffebee; color: #f44336; }

    .stat-info h3 {
        font-size: 28px;
        color: #333;
        margin-bottom: 5px;
    }

    .stat-info p {
        color: #666;
        font-size: 14px;
    }
</style>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📦</div>
        <div class="stat-info">
            <h3>{{ $totalBarang }}</h3>
            <p>Total Barang</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">📥</div>
        <div class="stat-info">
            <h3>{{ $stokMasukBulan }}</h3>
            <p>Stok Masuk Bulan Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">📤</div>
        <div class="stat-info">
            <h3>{{ $stokKeluarBulan }}</h3>
            <p>Stok Keluar Bulan Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">⚠️</div>
        <div class="stat-info">
            <h3>{{ $stokMinimal }}</h3>
            <p>Stok Menipis</p>
        </div>
    </div>
</div>

@if($barangMinimal->count() > 0)
<div class="alert alert-warning">
    ⚠️ <strong>Peringatan!</strong> {{ $barangMinimal->count() }} item stok menipis: 
    {{ $barangMinimal->pluck('nama')->join(', ') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <h3>Stok Barang Saat Ini</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $item)
            <tr>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->stok }}</td>
                <td>{{ $item->satuan }}</td>
                <td>
                    @if($item->stok > $item->min_stok)
                        <span class="badge badge-success">Aman</span>
                    @else
                        <span class="badge badge-danger">Menipis</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection