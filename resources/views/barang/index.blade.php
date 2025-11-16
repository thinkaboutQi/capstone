@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="page-header">
    <h2>Data Barang</h2>
    <p>Kelola data bahan baku dan produk</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>Daftar Barang</h3>
        <a href="{{ route('barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Min. Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $item)
            <tr>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->stok }}</td>
                <td>{{ $item->satuan }}</td>
                <td>{{ $item->min_stok }}</td>
                <td>
                    @if($item->stok > $item->min_stok)
                        <span class="badge badge-success">Aman</span>
                    @else
                        <span class="badge badge-danger">Menipis</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection