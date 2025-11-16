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
        // Set default periode (bulan ini)
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));

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
            ];
        }

        // Convert ke collection
        $laporan = collect($laporan);

        return view('laporan.index', compact('laporan', 'tanggalMulai', 'tanggalAkhir'));
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPDF(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));

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
                'stok_awal' => $stokAwal,
                'total_masuk' => $totalMasuk,
                'total_keluar' => $totalKeluar,
                'stok' => $item->stok,
                'satuan' => $item->satuan,
            ];
        }

        $laporan = collect($laporan);

        // Generate PDF dengan DOMPDF
        // Install dulu: composer require barryvdh/laravel-dompdf
        // Tambahkan di config/app.php providers: Barryvdh\DomPDF\ServiceProvider::class
        // Tambahkan di config/app.php aliases: 'PDF' => Barryvdh\DomPDF\Facade\Pdf::class
        
        $pdf = PDF::loadView('laporan.pdf', compact('laporan', 'tanggalMulai', 'tanggalAkhir'));
        
        $filename = 'Laporan_Inventory_' . date('Y-m-d_His') . '.pdf';
        
        return $pdf->download($filename);
    }
}