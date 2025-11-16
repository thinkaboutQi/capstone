@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="page-header">
    <h2>Tambah Barang Baru</h2>
    <p>Tambahkan bahan baku atau produk baru</p>
</div>

<div class="card">
    <form action="{{ route('barang.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama" required>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" required>
                <option value="Bahan Baku">Bahan Baku</option>
                <option value="Kemasan">Kemasan</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Stok Awal</label>
                <input type="number" name="stok" required>
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="satuan" placeholder="kg, liter, pcs" required>
            </div>
        </div>

        <div class="form-group">
            <label>Minimal Stok</label>
            <input type="number" name="min_stok" required>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection