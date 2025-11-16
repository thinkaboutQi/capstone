@extends('layouts.app')

@section('title', 'Stok Keluar')

@section('content')
<div class="page-header">
    <h2>Stok Keluar</h2>
    <p>Catat penggunaan bahan baku</p>
</div>

<div class="alert alert-warning">
    ℹ️ Stok keluar akan otomatis terupdate dari data penjualan POS Moka
</div>

<div class="card">
    <div class="card-header">
        <h3>Riwayat Stok Keluar</h3>
        <a href="{{ route('stok-keluar.create') }}" class="btn btn-primary">+ Input Manual</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Sumber</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokKeluar as $item)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->jumlah }} {{ $item->barang->satuan }}</td>
                <td>{{ $item->sumber }}</td>
                <td>{{ $item->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection