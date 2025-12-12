<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes - BLUEST Coffee Inventory System
|--------------------------------------------------------------------------
|
| Route untuk sistem manajemen inventory BLUEST Coffee dengan RBAC
|
*/

// Route untuk Guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    // Login Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Register Routes
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Route untuk User yang sudah login (Admin & Staff)
Route::middleware('auth')->group(function () {
    
    // Logout (semua role bisa logout)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard (semua role bisa akses)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // =======================================================================
    // ROUTE KHUSUS ADMIN (hanya admin yang bisa akses)
    // =======================================================================
    Route::middleware('role:admin')->group(function () {
        
        // Data Barang - CRUD lengkap (ADMIN ONLY)
        Route::resource('barang', BarangController::class);
        
        // Laporan - ADMIN ONLY
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'exportPDF'])->name('laporan.export');
    });
    
    // =======================================================================
    // ROUTE UNTUK ADMIN & STAFF (keduanya bisa akses)
    // =======================================================================
    Route::middleware('role:admin,staff')->group(function () {
        
        // Stok Masuk - Admin & Staff bisa input
        Route::get('/stok-masuk', [StokMasukController::class, 'index'])->name('stok-masuk.index');
        Route::get('/stok-masuk/create', [StokMasukController::class, 'create'])->name('stok-masuk.create');
        Route::post('/stok-masuk', [StokMasukController::class, 'store'])->name('stok-masuk.store');
        
        // Stok Keluar - Admin & Staff bisa input
        Route::get('/stok-keluar', [StokKeluarController::class, 'index'])->name('stok-keluar.index');
        Route::get('/stok-keluar/create', [StokKeluarController::class, 'create'])->name('stok-keluar.create');
        Route::post('/stok-keluar', [StokKeluarController::class, 'store'])->name('stok-keluar.store');
    });
    
    // =======================================================================
    // ROUTE EDIT/DELETE - ADMIN ONLY
    // =======================================================================
    Route::middleware('role:admin')->group(function () {
        // Stok Masuk - Edit & Delete (ADMIN ONLY)
        Route::get('/stok-masuk/{id}/edit', [StokMasukController::class, 'edit'])->name('stok-masuk.edit');
        Route::put('/stok-masuk/{id}', [StokMasukController::class, 'update'])->name('stok-masuk.update');
        Route::delete('/stok-masuk/{id}', [StokMasukController::class, 'destroy'])->name('stok-masuk.destroy');
        
        // Stok Keluar - Edit & Delete (ADMIN ONLY)
        Route::get('/stok-keluar/{id}/edit', [StokKeluarController::class, 'edit'])->name('stok-keluar.edit');
        Route::put('/stok-keluar/{id}', [StokKeluarController::class, 'update'])->name('stok-keluar.update');
        Route::delete('/stok-keluar/{id}', [StokKeluarController::class, 'destroy'])->name('stok-keluar.destroy');
    });
});
