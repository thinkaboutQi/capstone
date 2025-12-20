<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use Carbon\Carbon;
use PDF; // Untuk DOMPDF: composer require barryvdh/laravel-dompdf

class LaporanController extends Controller
{
    /**
     * Display laporan inventory
     */
    public function index(Request $request)
    {
        $periode = $request->input('periode', 'bulan_ini');
        
        // Set tanggal berdasarkan periode
        switch ($periode) {
            case 'hari_ini':
                $tanggalMulai = Carbon::today()->format('Y-m-d');
                $tanggalAkhir = Carbon::today()->format('Y-m-d');
                break;
            
            case 'minggu_ini':
                $tanggalMulai = Carbon::now()->startOfWeek()->format('Y-m-d');
                $tanggalAkhir = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            
            case 'bulan_ini':
                $tanggalMulai = Carbon::now()->startOfMonth()->format('Y-m-d');
                $tanggalAkhir = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            
            case 'custom':
                $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
                $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));
                break;
            
            default:
                $tanggalMulai = Carbon::now()->startOfMonth()->format('Y-m-d');
                $tanggalAkhir = Carbon::now()->format('Y-m-d');
        }

        // Ambil semua barang
        $barang = Barang::all();

        $laporan = [];

        foreach ($barang as $item) {
            // Hitung total stok masuk dalam periode
            $totalMasuk = StokMasuk::where('barang_id', $item->id)
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
                ->sum('jumlah');

            // Hitung total stok keluar dalam periode
            $totalKeluar = StokKeluar::where('barang_id', $item->id)
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
                ->sum('jumlah');

            // Hitung stok awal (stok sekarang - masuk + keluar dalam periode)
            $stokAwal = $item->stok - $totalMasuk + $totalKeluar;

            $laporan[] = [
                'id' => $item->id,
                'kode' => $item->kode,
                'nama' => $item->nama,
                'kategori' => $item->kategori,
                'stok_awal' => $stokAwal,
                'total_masuk' => $totalMasuk,
                'total_keluar' => $totalKeluar,
                'stok' => $item->stok,
                'satuan' => $item->satuan,
                'min_stok' => $item->min_stok,
            ];
        }

        // Convert ke collection
        $laporan = collect($laporan);

        return view('laporan.index', compact('laporan', 'tanggalMulai', 'tanggalAkhir', 'periode'));
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPDF(Request $request)
    {
        $periode = $request->input('periode', 'bulan_ini');
        
        // Set tanggal berdasarkan periode
        switch ($periode) {
            case 'hari_ini':
                $tanggalMulai = Carbon::today()->format('Y-m-d');
                $tanggalAkhir = Carbon::today()->format('Y-m-d');
                $periodeName = 'Hari Ini';
                break;
            
            case 'minggu_ini':
                $tanggalMulai = Carbon::now()->startOfWeek()->format('Y-m-d');
                $tanggalAkhir = Carbon::now()->endOfWeek()->format('Y-m-d');
                $periodeName = 'Minggu Ini';
                break;
            
            case 'bulan_ini':
                $tanggalMulai = Carbon::now()->startOfMonth()->format('Y-m-d');
                $tanggalAkhir = Carbon::now()->endOfMonth()->format('Y-m-d');
                $periodeName = 'Bulan Ini';
                break;
            
            case 'custom':
                $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
                $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));
                $periodeName = 'Custom';
                break;
            
            default:
                $tanggalMulai = Carbon::now()->startOfMonth()->format('Y-m-d');
                $tanggalAkhir = Carbon::now()->format('Y-m-d');
                $periodeName = 'Bulan Ini';
        }

        // Ambil semua barang
        $barang = Barang::all();

        $laporan = [];

        foreach ($barang as $item) {
            $totalMasuk = StokMasuk::where('barang_id', $item->id)
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
                ->sum('jumlah');

            $totalKeluar = StokKeluar::where('barang_id', $item->id)
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
                ->sum('jumlah');

            $stokAwal = $item->stok - $totalMasuk + $totalKeluar;

            $laporan[] = [
    'kode' => $item->kode,
    'nama' => $item->nama,
    'kategori' => $item->kategori,
    'stok_awal' => $stokAwal,
    'total_masuk' => $totalMasuk,
    'total_keluar' => $totalKeluar,
    'stok' => $item->stok,
    'satuan' => $item->satuan,
    'min_stok' => $item->min_stok ?? 0, // tambahkan ini
];

        }

        $laporan = collect($laporan);

        // Generate PDF dengan DOMPDF
        $pdf = PDF::loadView('laporan.pdf', compact('laporan', 'tanggalMulai', 'tanggalAkhir', 'periodeName'));
        
        $filename = 'Laporan_Inventory_' . $periodeName . '_' . date('Y-m-d_His') . '.pdf';
        
        return $pdf->download($filename);
    }
}