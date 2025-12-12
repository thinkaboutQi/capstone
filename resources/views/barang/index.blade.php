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
                <th>Waktu Ditambahkan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $item)
            <tr>
                <td>{{ $item->kode }}</td>
                <td><strong>{{ $item->nama }}</strong></td>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->stok }}</td>
                <td>{{ $item->satuan }}</td>
                <td>{{ $item->min_stok }}</td>
                <td>
                    <span class="time-badge" data-timestamp="{{ $item->created_at->timestamp }}">
                        <span class="time-icon">🕐</span>
                        <span class="time-text">{{ $item->created_at->diffForHumans() }}</span>
                    </span>
                    <div class="time-detail">
                        {{ $item->created_at->format('d M Y, H:i') }} WIB
                    </div>
                </td>
                <td>
                    @if($item->stok > $item->min_stok)
                        <span class="badge badge-success">Aman</span>
                    @else
                        <span class="badge badge-danger">Menipis</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('styles')
<style>
    .time-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .time-badge:hover {
        transform: scale(1.05);
    }

    .time-icon {
        font-size: 14px;
        animation: rotate 2s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .time-text {
        text-transform: capitalize;
    }

    .time-detail {
        font-size: 11px;
        color: #999;
        margin-top: 4px;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
        margin-right: 5px;
    }

    /* Live Update Indicator */
    .live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        background: #4caf50;
        color: white;
        border-radius: 12px;
        font-size: 11px;
        margin-left: 10px;
        animation: pulse-live 2s infinite;
    }

    @keyframes pulse-live {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }

    .live-dot {
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Update waktu secara realtime setiap 1 menit
    function updateRelativeTime() {
        const timeBadges = document.querySelectorAll('.time-badge');
        
        timeBadges.forEach(badge => {
            const timestamp = parseInt(badge.dataset.timestamp);
            const now = Math.floor(Date.now() / 1000);
            const diff = now - timestamp;
            
            const timeText = badge.querySelector('.time-text');
            timeText.textContent = formatRelativeTime(diff);
        });
    }

    function formatRelativeTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        const weeks = Math.floor(days / 7);
        const months = Math.floor(days / 30);
        const years = Math.floor(days / 365);

        if (seconds < 60) return 'baru saja';
        if (minutes < 60) return minutes + ' menit yang lalu';
        if (hours < 24) return hours + ' jam yang lalu';
        if (days < 7) return days + ' hari yang lalu';
        if (weeks < 4) return weeks + ' minggu yang lalu';
        if (months < 12) return months + ' bulan yang lalu';
        return years + ' tahun yang lalu';
    }

    // Update setiap 1 menit
    setInterval(updateRelativeTime, 60000);

    // Update pertama kali saat load
    updateRelativeTime();

    // Add live indicator to header
    window.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.card-header h3');
        if (header) {
            const liveIndicator = document.createElement('span');
            liveIndicator.className = 'live-indicator';
            liveIndicator.innerHTML = '<span class="live-dot"></span> Live Update';
            header.appendChild(liveIndicator);
        }
    });
</script>
@endpush
@endsection