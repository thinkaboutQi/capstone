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

    // Set tanggal dengan Carbon lengkap (handle datetime)
    switch ($periode) {
        case 'hari_ini':
            $tanggalMulai = Carbon::today()->startOfDay();
            $tanggalAkhir = Carbon::today()->endOfDay();
            break;
        case 'minggu_ini':
            $tanggalMulai = Carbon::now()->startOfWeek()->startOfDay();
            $tanggalAkhir = Carbon::now()->endOfWeek()->endOfDay();
            break;
        case 'bulan_ini':
            $tanggalMulai = Carbon::now()->startOfMonth()->startOfDay();
            $tanggalAkhir = Carbon::now()->endOfMonth()->endOfDay();
            break;
        case 'custom':
            $tanggalMulai = Carbon::parse($request->input('tanggal_mulai', Carbon::now()->startOfMonth()))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', Carbon::now()))->endOfDay();
            break;
        default:
            $tanggalMulai = Carbon::now()->startOfMonth()->startOfDay();
            $tanggalAkhir = Carbon::now()->endOfDay();
    }

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

        // Hitung stok awal
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

    $laporan = collect($laporan);

    // FORMAT TANGGAL UNTUK VIEW (Y-m-d string untuk input date)
    $tanggalMulaiString = $tanggalMulai->format('Y-m-d');
    $tanggalAkhirString = $tanggalAkhir->format('Y-m-d');

    return view('laporan.index', [
        'laporan' => $laporan,
        'tanggalMulai' => $tanggalMulaiString,  // String Y-m-d untuk input
        'tanggalAkhir' => $tanggalAkhirString,  // String Y-m-d untuk input
        'periode' => $periode
    ]);
}

    /**
     * Export laporan ke PDF
     */
    public function exportPDF(Request $request)
{
    $periode = $request->input('periode', 'bulan_ini');

    switch ($periode) {
        case 'hari_ini':
            $tanggalMulai = Carbon::today()->startOfDay();
            $tanggalAkhir = Carbon::today()->endOfDay();
            $periodeName = 'Hari Ini';
            break;
        case 'minggu_ini':
            $tanggalMulai = Carbon::now()->startOfWeek()->startOfDay();
            $tanggalAkhir = Carbon::now()->endOfWeek()->endOfDay();
            $periodeName = 'Minggu Ini';
            break;
        case 'bulan_ini':
            $tanggalMulai = Carbon::now()->startOfMonth()->startOfDay();
            $tanggalAkhir = Carbon::now()->endOfMonth()->endOfDay();
            $periodeName = 'Bulan Ini';
            break;
        case 'custom':
            $tanggalMulai = Carbon::parse($request->input('tanggal_mulai', Carbon::now()->startOfMonth()))->startOfDay();
            $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir', Carbon::now()))->endOfDay();
            $periodeName = 'Custom';
            break;
        default:
            $tanggalMulai = Carbon::now()->startOfMonth()->startOfDay();
            $tanggalAkhir = Carbon::now()->endOfDay();
            $periodeName = 'Bulan Ini';
    }

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
            'min_stok' => $item->min_stok ?? 0,
        ];
    }

    $laporan = collect($laporan);

    // Format tanggal untuk PDF
    $tanggalMulaiFormatted = $tanggalMulai->format('d M Y');
    $tanggalAkhirFormatted = $tanggalAkhir->format('d M Y');

    $pdf = PDF::loadView('laporan.pdf', [
        'laporan' => $laporan,
        'tanggalMulai' => $tanggalMulaiFormatted,
        'tanggalAkhir' => $tanggalAkhirFormatted,
        'periodeName' => $periodeName
    ]);

    $filename = 'Laporan_Inventory_' . $periodeName . '_' . date('Y-m-d_His') . '.pdf';

    return $pdf->download($filename);
}
}