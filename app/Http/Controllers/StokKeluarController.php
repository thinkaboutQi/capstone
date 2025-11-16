<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\StokKeluar;
use Illuminate\Support\Facades\DB;

class StokKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stokKeluar = StokKeluar::with('barang')
            ->orderBy('tanggal', 'desc')
            ->get();
            
        return view('stok-keluar.index', compact('stokKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::where('stok', '>', 0)
            ->orderBy('nama', 'asc')
            ->get();
            
        return view('stok-keluar.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string',
        ]);

        // Cek stok mencukupi
        $barang = Barang::findOrFail($validated['barang_id']);
        
        if ($barang->stok < $validated['jumlah']) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Stok tidak mencukupi! Stok tersedia: {$barang->stok} {$barang->satuan}");
        }

        // Gunakan database transaction
        DB::transaction(function () use ($validated, $barang) {
            // Simpan data stok keluar
            $validated['sumber'] = 'Manual Input';
            StokKeluar::create($validated);

            // Kurangi stok barang
            $barang->stok -= $validated['jumlah'];
            $barang->save();
        });

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Stok keluar berhasil dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stokKeluar = StokKeluar::with('barang')->findOrFail($id);
        return view('stok-keluar.show', compact('stokKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);
        $barang = Barang::orderBy('nama', 'asc')->get();
        return view('stok-keluar.edit', compact('stokKeluar', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string',
        ]);

        $stokKeluar = StokKeluar::findOrFail($id);

        // Gunakan database transaction
        DB::transaction(function () use ($stokKeluar, $validated) {
            // Kembalikan stok lama
            $barang = Barang::findOrFail($stokKeluar->barang_id);
            $barang->stok += $stokKeluar->jumlah;
            $barang->save();

            // Update stok keluar
            $stokKeluar->update($validated);

            // Kurangi stok baru
            $barangBaru = Barang::findOrFail($validated['barang_id']);
            
            // Validasi stok mencukupi
            if ($barangBaru->stok < $validated['jumlah']) {
                throw new \Exception("Stok tidak mencukupi! Stok tersedia: {$barangBaru->stok} {$barangBaru->satuan}");
            }
            
            $barangBaru->stok -= $validated['jumlah'];
            $barangBaru->save();
        });

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Stok keluar berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);

        // Gunakan database transaction
        DB::transaction(function () use ($stokKeluar) {
            // Kembalikan stok barang
            $barang = Barang::findOrFail($stokKeluar->barang_id);
            $barang->stok += $stokKeluar->jumlah;
            $barang->save();

            // Hapus record stok keluar
            $stokKeluar->delete();
        });

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Stok keluar berhasil dihapus!');
    }
}