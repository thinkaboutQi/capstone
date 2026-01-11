<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StokMasuk extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stok_masuk';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tanggal',
        'barang_id',
        'jumlah',
        'satuan',
        'pemasok',
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
}