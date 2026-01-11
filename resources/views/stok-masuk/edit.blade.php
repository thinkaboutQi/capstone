@extends('layouts.app')

@section('title', 'Edit Stok Masuk')

@section('content')
<div class="page-header">
    <h2>Edit Stok Masuk</h2>
    <p>Ubah data penerimaan stok barang</p>
</div>

<div class="card">
    <form action="{{ route('stok-masuk.update', $stokMasuk->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="{{ old('tanggal', $stokMasuk->tanggal) }}" required>
            @error('tanggal')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Barang</label>
            <select name="barang_id" required>
                <option value="">Pilih Barang</option>
                @foreach($barang as $item)
                    <option value="{{ $item->id }}" {{ old('barang_id', $stokMasuk->barang_id) == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
            @error('barang_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" value="{{ old('jumlah', $stokMasuk->jumlah) }}" step="0.01" min="1" required>
                @error('jumlah')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="satuan" value="{{ old('satuan', $stokMasuk->satuan ?? '') }}" placeholder="kg, pcs, liter, dll">
                @error('satuan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Pemasok</label>
            <input type="text" name="pemasok" value="{{ old('pemasok', $stokMasuk->pemasok ?? '') }}" placeholder="Nama pemasok">
            @error('pemasok')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3">{{ old('keterangan', $stokMasuk->keterangan) }}</textarea>
            @error('keterangan')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('stok-masuk.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection