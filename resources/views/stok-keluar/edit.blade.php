@extends('layouts.app')

@section('title', 'Edit Stok Keluar')

@section('content')

<div class="page-header">
    <h2>📤 Edit Stok Keluar</h2>
    <p>Ubah data pengeluaran stok barang</p>
</div>

<div class="card">
    <form action="{{ route('stok-keluar.update', $stokKeluar->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>📅 Tanggal</label>
            <input type="date" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($stokKeluar->tanggal)->format('Y-m-d')) }}" required>
            @error('tanggal')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Barang</label>
            <select name="barang_id" id="barangSelect" required>
                <option value="">Pilih Barang</option>
                @foreach($barang as $item)
                    <option value="{{ $item->id }}" 
                            data-satuan="{{ $item->satuan }}"
                            data-stok="{{ $item->stok }}"
                            {{ old('barang_id', $stokKeluar->barang_id) == $item->id ? 'selected' : '' }}>
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
                <input type="number" name="jumlah" id="jumlahInput" value="{{ old('jumlah', $stokKeluar->jumlah) }}" step="0.01" min="0.01" required>
                @error('jumlah')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="satuan" id="satuanInput" value="{{ old('satuan', $stokKeluar->satuan ?? '') }}" readonly style="background-color: #f5f5f5; cursor: not-allowed;">
                @error('satuan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3" placeholder="Masukkan keterangan pengeluaran...">{{ old('keterangan', $stokKeluar->keterangan) }}</textarea>
            @error('keterangan')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('stok-keluar.index') }}" class="btn btn-secondary">← Batal</a>
            <button type="submit" class="btn btn-primary">💾 Update</button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .stok-info {
        background: #e3f2fd;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #2196f3;
    }
    .stok-info span {
        color: #1565c0;
        font-size: 14px;
    }
    .stok-info strong {
        color: #0d47a1;
    }
    .stok-warning {
        background: #fff3e0 !important;
        border-left-color: #ff9800 !important;
    }
    .stok-warning span {
        color: #e65100 !important;
    }
    .stok-warning strong {
        color: #bf360c !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('barangSelect');
    const satuanInput = document.getElementById('satuanInput');
    const jumlahInput = document.getElementById('jumlahInput');
    const stokInfo = document.getElementById('stokInfo');
    const stokTersedia = document.getElementById('stokTersedia');

    // Simpan jumlah lama untuk perhitungan stok
    const jumlahLama = {{ $stokKeluar->jumlah }};

    function updateSatuanDanStok() {
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const satuan = selectedOption.getAttribute('data-satuan');
            let stok = parseFloat(selectedOption.getAttribute('data-stok')) || 0;
            
            // Jika barang yang dipilih sama dengan barang sebelumnya,
            // tambahkan jumlah lama ke stok (karena akan dikembalikan)
            if (selectedOption.value == {{ $stokKeluar->barang_id }}) {
                stok = stok + jumlahLama;
            }
            
            // Update satuan
            satuanInput.value = satuan || '';
            
            // Update info stok
            stokTersedia.textContent = stok.toFixed(0) + ' ' + (satuan || '');
            stokInfo.style.display = 'block';
            
            // Set max jumlah
            jumlahInput.setAttribute('max', stok);
            
            // Warning jika stok menipis
            if (stok < 10) {
                stokInfo.classList.add('stok-warning');
            } else {
                stokInfo.classList.remove('stok-warning');
            }
        } else {
            satuanInput.value = '';
            stokInfo.style.display = 'none';
            jumlahInput.removeAttribute('max');
        }
    }

    // Validasi jumlah tidak melebihi stok
    jumlahInput.addEventListener('input', function() {
        const max = parseFloat(this.getAttribute('max'));
        const value = parseFloat(this.value);
        
        if (max && value > max) {
            this.setCustomValidity('Jumlah melebihi stok tersedia (' + max + ')');
        } else {
            this.setCustomValidity('');
        }
    });

    // Event listener
    barangSelect.addEventListener('change', updateSatuanDanStok);
    
    // Jalankan saat halaman load untuk data existing
    updateSatuanDanStok();
});
</script>
@endpush

@endsection