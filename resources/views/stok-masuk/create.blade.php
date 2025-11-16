@extends('layouts.app')

@section('title', 'Tambah Stok Masuk')

@section('content')
<div class="page-header">
    <h2>Tambah Stok Masuk</h2>
    <p>Catat penerimaan bahan baku</p>
</div>

<div class="card">
    <form action="{{ route('stok-masuk.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label>Barang</label>
            <select name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barang as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->satuan }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Pemasok</label>
            <input type="text" name="pemasok" required>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3"></textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('stok-masuk.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection