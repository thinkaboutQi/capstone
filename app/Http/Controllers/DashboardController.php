<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard dengan statistik
     */
    public function index()
    {
        // Ambil tanggal awal bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Hitung statistik
        $totalBarang = Barang::count();
        
        $stokMasukBulan = StokMasuk::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->count();
        
        $stokKeluarBulan = StokKeluar::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->count();
        
        // Barang dengan stok minimal atau di bawahnya
        $stokMinimal = Barang::whereRaw('stok < min_stok')->count();
        
        // Ambil semua barang untuk tabel
        $barang = Barang::orderBy('nama', 'asc')->get();
        
        // Barang yang stok menipis (untuk alert)
        $barangMinimal = Barang::whereRaw('stok <= min_stok')
            ->orderBy('stok', 'asc')
            ->get();

        return view('dashboard', compact(
            'totalBarang',
            'stokMasukBulan',
            'stokKeluarBulan',
            'stokMinimal',
            'barang',
            'barangMinimal'
        ));
    }
}