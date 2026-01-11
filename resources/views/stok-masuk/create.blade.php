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
            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
            @error('tanggal')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Barang</label>
            <select name="barang_id" id="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barang as $item)
                    <option value="{{ $item->id }}" data-satuan="{{ $item->satuan }}" {{ old('barang_id') == $item->id ? 'selected' : '' }}>
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
                <input type="number" name="jumlah" value="{{ old('jumlah') }}" step="0.01" min="0.01" required>
                @error('jumlah')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="satuan" id="satuan" value="{{ old('satuan') }}" placeholder="Pilih barang terlebih dahulu" readonly style="background-color: #f5f5f5; cursor: not-allowed;">
                @error('satuan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Pemasok</label>
            <input type="text" name="pemasok" value="{{ old('pemasok') }}" placeholder="Nama pemasok" required>
            @error('pemasok')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('stok-masuk.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<script>
// Auto-fill satuan ketika barang dipilih
document.getElementById('barang_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const satuan = selectedOption.getAttribute('data-satuan');
    const satuanInput = document.getElementById('satuan');
    
    if (satuan) {
        satuanInput.value = satuan;
        satuanInput.style.backgroundColor = '#f5f5f5';
    } else {
        satuanInput.value = '';
        satuanInput.placeholder = 'Pilih barang terlebih dahulu';
    }
});

// Trigger saat halaman load (untuk old() value ketika validasi gagal)
window.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('barang_id');
    if (barangSelect.value) {
        barangSelect.dispatchEvent(new Event('change'));
    }
});
</script>

@endsection