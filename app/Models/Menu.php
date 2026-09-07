<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $primaryKey = 'id_menu';

    public $timestamps = false;

    protected $fillable = [
        'nama_menu',
        'kategori',
        'harga',
        'stok',
        'deskripsi',
        'gambar',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'stok' => 'integer',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id_menu';
    }

    /**
     * Relasi ke ulasan
     */
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_menu', 'id_menu');
    }

    /**
     * Relasi ke detail pesanan
     */
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu', 'id_menu');
    }

    /**
     * Relasi ke histori stok
     */
    public function stokHistory()
    {
        return $this->hasMany(Stok::class, 'id_menu', 'id_menu');
    }

    /**
     * Cek apakah menu tersedia
     */
    public function isAvailable(): bool
    {
        return $this->status === 'tersedia' && $this->stok > 0;
    }

    /**
     * Cek apakah stok menipis (<= 5)
     */
    public function isLowStock(): bool
    {
        return $this->status === 'tersedia' && $this->stok > 0 && $this->stok <= 5;
    }

    /**
     * Cek apakah menu habis
     */
    public function isOutOfStock(): bool
    {
        return $this->status === 'habis' || $this->stok <= 0;
    }
}
