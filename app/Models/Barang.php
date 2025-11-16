<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'barang';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'stok',
        'satuan',
        'min_stok',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'stok' => 'decimal:2',
        'min_stok' => 'decimal:2',
    ];

    /**
     * Relasi ke StokMasuk
     */
    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class, 'barang_id');
    }

    /**
     * Relasi ke StokKeluar
     */
    public function stokKeluar()
    {
        return $this->hasMany(StokKeluar::class, 'barang_id');
    }

    /**
     * Check apakah stok menipis
     */
    public function isStokMinimal()
    {
        return $this->stok <= $this->min_stok;
    }

    /**
     * Get status stok
     */
    public function getStatusStokAttribute()
    {
        if ($this->stok <= 0) {
            return 'Habis';
        } elseif ($this->stok <= $this->min_stok) {
            return 'Menipis';
        } else {
            return 'Aman';
        }
    }

    /**
     * Scope untuk barang dengan stok menipis
     */
    public function scopeStokMinimal($query)
    {
        return $query->whereRaw('stok <= min_stok');
    }

    /**
     * Scope untuk barang dengan stok aman
     */
    public function scopeStokAman($query)
    {
        return $query->whereRaw('stok > min_stok');
    }
}