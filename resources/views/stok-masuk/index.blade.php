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
                @if(Auth::user()->role === 'admin')
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($stokMasuk as $item)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td><strong>{{ $item->jumlah }} {{ $item->barang->satuan }}</strong></td>
                <td>{{ $item->pemasok }}</td>
                <td>{{ $item->keterangan }}</td>
                @if(Auth::user()->role === 'admin')
                    <td>
                        <a href="{{ route('stok-masuk.edit', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('stok-masuk.destroy', $item->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('styles')
<style>
    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
        margin-right: 5px;
    }
</style>
@endpush
@endsection