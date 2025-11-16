@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="page-header">
    <h2>Edit Barang</h2>
    <p>Ubah data barang</p>
</div>

<div class="card">
    <form action="{{ route('barang.update', $barang->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" value="{{ $barang->kode }}" disabled>
        </div>

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama" value="{{ $barang->nama }}" required>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" required>
                <option value="Bahan Baku" {{ $barang->kategori == 'Bahan Baku' ? 'selected' : '' }}>Bahan Baku</option>
                <option value="Kemasan" {{ $barang->kategori == 'Kemasan' ? 'selected' : '' }}>Kemasan</option>
                <option value="Lainnya" {{ $barang->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" value="{{ $barang->stok }}" required>
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="satuan" value="{{ $barang->satuan }}" required>
            </div>
        </div>

        <div class="form-group">
            <label>Minimal Stok</label>
            <input type="number" name="min_stok" value="{{ $barang->min_stok }}" required>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection