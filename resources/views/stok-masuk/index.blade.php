@extends('layouts.app')

@section('title', 'Stok Masuk')

@section('content')
<div class="page-header">
    <h2>Stok Masuk</h2>
    <p>Catat pembelian dan penerimaan bahan baku</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>Input Stok Masuk</h3>
        <a href="{{ route('stok-masuk.create') }}" class="btn btn-primary">+ Tambah Stok Masuk</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Pemasok</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokMasuk as $item)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->jumlah }} {{ $item->barang->satuan }}</td>
                <td>{{ $item->pemasok }}</td>
                <td>{{ $item->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection