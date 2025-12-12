<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - BLUEST Coffee</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
            text-decoration: none;
        }

        .navbar-brand img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-user span {
            color: #333;
            font-weight: 500;
        }

        /* Notification Bell */
        .notification-bell {
            position: relative;
            cursor: pointer;
        }

        .bell-icon {
            font-size: 24px;
            color: #667eea;
            transition: transform 0.3s;
        }

        .bell-icon:hover {
            transform: scale(1.1);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #f44336;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Notification Dropdown */
        .notification-dropdown {
            position: absolute;
            top: 60px;
            right: 100px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .notification-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h3 {
            font-size: 16px;
            color: #333;
        }

        .notification-count {
            background: #f44336;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
        }

        .notification-list {
            max-height: 320px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }

        .notification-item:hover {
            background: #f9f9f9;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }

        .notification-icon {
            font-size: 20px;
        }

        .notification-title {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .notification-message {
            color: #666;
            font-size: 13px;
            margin-left: 30px;
        }

        .notification-stock {
            margin-left: 30px;
            margin-top: 5px;
            font-size: 12px;
            color: #999;
        }

        .stock-warning {
            color: #f44336;
            font-weight: 600;
        }

        .notification-empty {
            padding: 40px 20px;
            text-align: center;
            color: #999;
        }

        .notification-empty-icon {
            font-size: 48px;
            margin-bottom: 10px;
            opacity: 0.3;
        }

        .notification-footer {
            padding: 12px 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }

        .notification-footer a {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .notification-footer a:hover {
            text-decoration: underline;
        }

        .btn-logout {
            padding: 8px 20px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn-logout:hover {
            background: #c82333;
        }

        .main-content {
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: white;
            min-height: calc(100vh - 70px);
            padding: 20px 0;
        }

        .menu-item {
            padding: 15px 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.3s;
            color: #333;
            text-decoration: none;
            display: block;
        }

        .menu-item:hover {
            background: #f0f0f0;
        }

        .menu-item.active {
            background: #667eea;
            color: white;
            border-right: 4px solid #5568d3;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        .page-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .page-header h2 {
            color: #333;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #666;
            font-size: 14px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #f5f5f5;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            color: #666;
        }

        table tr:hover {
            background: #f9f9f9;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #e8f5e9;
            color: #4caf50;
        }

        .badge-warning {
            background: #fff3e0;
            color: #ff9800;
        }

        .badge-danger {
            background: #ffebee;
            color: #f44336;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .navbar-brand {
                font-size: 16px;
            }

            .navbar-brand img {
                width: 35px;
                height: 35px;
            }

            .notification-dropdown {
                right: 20px;
                width: calc(100vw - 40px);
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <img src="https://uploads.onecompiler.io/432w6j563/444sfg9pv/Picture1.jpg" alt="BLUEST Coffee Logo">
            <span>BLUEST Coffee</span>
        </a>
        <div class="navbar-user">
            <!-- Notification Bell -->
            <div class="notification-bell" onclick="toggleNotification()">
                <span class="bell-icon">🔔</span>
                @php
                    $stokMinimal = \App\Models\Barang::whereRaw('stok <= min_stok')->count();
                @endphp
                @if($stokMinimal > 0)
                    <span class="notification-badge">{{ $stokMinimal }}</span>
                @endif
            </div>

            <span>{{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>

        <!-- Notification Dropdown -->
        <div class="notification-dropdown" id="notificationDropdown">
            <div class="notification-header">
                <h3>📦 Notifikasi Stok</h3>
                @if($stokMinimal > 0)
                    <span class="notification-count">{{ $stokMinimal }}</span>
                @endif
            </div>

            <div class="notification-list">
                @php
                    $barangMinimal = \App\Models\Barang::whereRaw('stok <= min_stok')
                        ->orderBy('stok', 'asc')
                        ->get();
                @endphp

                @if($barangMinimal->count() > 0)
                    @foreach($barangMinimal as $item)
                        <div class="notification-item">
                            <div class="notification-item-header">
                                <span class="notification-icon">⚠️</span>
                                <span class="notification-title">{{ $item->nama }}</span>
                            </div>
                            <div class="notification-message">
                                Stok menipis! Segera lakukan pemesanan ulang.
                            </div>
                            <div class="notification-stock">
                                Stok tersisa: <span class="stock-warning">{{ $item->stok }} {{ $item->satuan }}</span> 
                                (Min: {{ $item->min_stok }} {{ $item->satuan }})
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="notification-empty">
                        <div class="notification-empty-icon">✅</div>
                        <p>Semua stok aman!</p>
                        <p style="font-size: 12px; margin-top: 5px;">Tidak ada barang yang menipis</p>
                    </div>
                @endif
            </div>

            @if($barangMinimal->count() > 0)
                <div class="notification-footer">
                    <a href="{{ route('barang.index') }}">Lihat Semua Barang →</a>
                </div>
            @endif
        </div>
    </nav>

    <div class="main-content">
        <div class="sidebar">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span>📊</span> Dashboard
            </a>
            
            <!-- Menu Data Barang - ADMIN ONLY -->
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('barang.index') }}" class="menu-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                    <span>📦</span> Data Barang
                </a>
            @endif
            
            <!-- Menu Stok Masuk - ADMIN & STAFF -->
            <a href="{{ route('stok-masuk.index') }}" class="menu-item {{ request()->routeIs('stok-masuk.*') ? 'active' : '' }}">
                <span>📥</span> Stok Masuk
            </a>
            
            <!-- Menu Stok Keluar - ADMIN & STAFF -->
            <a href="{{ route('stok-keluar.index') }}" class="menu-item {{ request()->routeIs('stok-keluar.*') ? 'active' : '' }}">
                <span>📤</span> Stok Keluar
            </a>
            
            <!-- Menu Laporan - ADMIN ONLY -->
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('laporan.index') }}" class="menu-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <span>📋</span> Laporan
                </a>
            @endif
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function toggleNotification() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const bell = document.querySelector('.notification-bell');
            
            if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Auto refresh notification every 5 minutes
        setInterval(function() {
            location.reload();
        }, 300000); // 5 minutes
    </script>

    @stack('scripts')
</body>
</html>