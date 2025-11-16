<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StokKeluar extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stok_keluar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tanggal',
        'barang_id',
        'jumlah',
        'sumber',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    /**
     * Relasi ke Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Get formatted tanggal
     */
    public function getTanggalFormatAttribute()
    {
        return Carbon::parse($this->tanggal)->format('d/m/Y');
    }

    /**
     * Check apakah dari POS Moka
     */
    public function isDariMoka()
    {
        return $this->sumber === 'POS Moka';
    }

    /**
     * Check apakah input manual
     */
    public function isManual()
    {
        return $this->sumber === 'Manual Input';
    }

    /**
     * Scope untuk filter berdasarkan bulan
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year);
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopePeriode($query, $start, $end)
    {
        return $query->whereBetween('tanggal', [$start, $end]);
    }

    /**
     * Scope untuk filter berdasarkan barang
     */
    public function scopeByBarang($query, $barangId)
    {
        return $query->where('barang_id', $barangId);
    }

    /**
     * Scope untuk filter berdasarkan sumber
     */
    public function scopeBySumber($query, $sumber)
    {
        return $query->where('sumber', $sumber);
    }
}