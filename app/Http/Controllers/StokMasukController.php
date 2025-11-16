<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\StokMasuk;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stokMasuk = StokMasuk::with('barang')
            ->orderBy('tanggal', 'desc')
            ->get();
            
        return view('stok-masuk.index', compact('stokMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::orderBy('nama', 'asc')->get();
        return view('stok-masuk.create', compact('barang'));
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
            'pemasok' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // Gunakan database transaction untuk memastikan konsistensi data
        DB::transaction(function () use ($validated) {
            // Simpan data stok masuk
            StokMasuk::create($validated);

            // Update stok barang
            $barang = Barang::findOrFail($validated['barang_id']);
            $barang->stok += $validated['jumlah'];
            $barang->save();
        });

        return redirect()->route('stok-masuk.index')
            ->with('success', 'Stok masuk berhasil dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stokMasuk = StokMasuk::with('barang')->findOrFail($id);
        return view('stok-masuk.show', compact('stokMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);
        $barang = Barang::orderBy('nama', 'asc')->get();
        return view('stok-masuk.edit', compact('stokMasuk', 'barang'));
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
            'pemasok' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $stokMasuk = StokMasuk::findOrFail($id);

        // Gunakan database transaction
        DB::transaction(function () use ($stokMasuk, $validated) {
            // Kembalikan stok lama
            $barang = Barang::findOrFail($stokMasuk->barang_id);
            $barang->stok -= $stokMasuk->jumlah;
            $barang->save();

            // Update stok masuk
            $stokMasuk->update($validated);

            // Tambah stok baru
            $barangBaru = Barang::findOrFail($validated['barang_id']);
            $barangBaru->stok += $validated['jumlah'];
            $barangBaru->save();
        });

        return redirect()->route('stok-masuk.index')
            ->with('success', 'Stok masuk berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);

        // Gunakan database transaction
        DB::transaction(function () use ($stokMasuk) {
            // Kurangi stok barang
            $barang = Barang::findOrFail($stokMasuk->barang_id);
            $barang->stok -= $stokMasuk->jumlah;
            $barang->save();

            // Hapus record stok masuk
            $stokMasuk->delete();
        });

        return redirect()->route('stok-masuk.index')
            ->with('success', 'Stok masuk berhasil dihapus!');
    }
}