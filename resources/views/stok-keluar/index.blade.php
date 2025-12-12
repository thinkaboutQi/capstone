@extends('layouts.app')

@section('title', 'Stok Keluar')

@section('content')
<div class="page-header">
    <h2>Stok Keluar</h2>
    <p>Catat penggunaan bahan baku</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>Riwayat Stok Keluar</h3>
        <a href="{{ route('stok-keluar.create') }}" class="btn btn-primary">+ Masukan Stok Keluar</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Sumber</th>
                <th>Keterangan</th>
                @if(Auth::user()->role === 'admin')
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($stokKeluar as $item)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td><strong>{{ $item->jumlah }} {{ $item->barang->satuan }}</strong></td>
                <td>
                    @if($item->sumber === 'POS Moka')
                        <span class="badge badge-success">{{ $item->sumber }}</span>
                    @else
                        <span class="badge badge-warning">{{ $item->sumber }}</span>
                    @endif
                </td>
                <td>{{ $item->keterangan }}</td>
                @if(Auth::user()->role === 'admin')
                    <td>
                        <a href="{{ route('stok-keluar.edit', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('stok-keluar.destroy', $item->id) }}" method="POST" style="display: inline;">
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