@extends('layouts.app')

@section('title', 'Input Stok Keluar')

@section('content')
<div class="page-header">
    <h2>Input Stok Keluar Manual</h2>
    <p>Catat penggunaan bahan baku manual</p>
</div>

<div class="card">
    <form action="{{ route('stok-keluar.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label>Barang</label>
            <select name="barang_id" id="barangSelect" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barang as $item)
                    <option value="{{ $item->id }}" data-stok="{{ $item->stok }}" data-satuan="{{ $item->satuan }}">
                        {{ $item->nama }} (Stok: {{ $item->stok }} {{ $item->satuan }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" id="jumlahInput" step="0.01" required>
            <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;" id="stokInfo"></small>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3" placeholder="Contoh: Pemakaian produksi, Rusak/Hilang, dll"></textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('stok-keluar.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Validasi stok saat memilih barang
    document.getElementById('barangSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stok = selectedOption.getAttribute('data-stok');
        const satuan = selectedOption.getAttribute('data-satuan');
        
        const stokInfo = document.getElementById('stokInfo');
        if (stok) {
            stokInfo.textContent = 'Stok tersedia: ' + stok + ' ' + satuan;
            document.getElementById('jumlahInput').setAttribute('max', stok);
        } else {
            stokInfo.textContent = '';
            document.getElementById('jumlahInput').removeAttribute('max');
        }
    });

    // Validasi jumlah input
    document.getElementById('jumlahInput').addEventListener('input', function() {
        const max = parseFloat(this.getAttribute('max'));
        const value = parseFloat(this.value);
        
        if (max && value > max) {
            alert('Jumlah melebihi stok yang tersedia! Maksimal: ' + max);
            this.value = max;
        }
    });
</script>
@endpush
@endsection