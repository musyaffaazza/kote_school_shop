<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_promo',
        'deskripsi',
        'jenis_promo',
        'nilai_promo',
        'satuan_nilai',
        'kode_voucher',
        'periode_mulai',
        'periode_selesai',
        'gambar',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'periode_mulai' => 'date',
            'periode_selesai' => 'date',
        ];
    }

    /**
     * Scope: only active promos.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: only promos that haven't expired yet.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('periode_selesai')
                ->orWhere('periode_selesai', '>=', now()->toDateString());
        });
    }

    /**
     * Get the uppercase jenis label.
     */
    public function getJenisLabelAttribute(): string
    {
        return strtoupper($this->jenis_promo);
    }

    /**
     * Get formatted nilai promo with unit.
     */
    public function getNilaiFormattedAttribute(): string
    {
        if ($this->satuan_nilai === 'persen') {
            return $this->nilai_promo.'%';
        }

        return 'Rp'.number_format($this->nilai_promo, 0, ',', '.');
    }

    /**
     * Get formatted periode berlaku string.
     */
    public function getPeriodeFormattedAttribute(): string
    {
        if ($this->periode_mulai && $this->periode_selesai) {
            return $this->periode_mulai->format('d/m/Y').' - '.$this->periode_selesai->format('d/m/Y');
        }

        if ($this->periode_mulai) {
            return 'Mulai '.$this->periode_mulai->format('d/m/Y');
        }

        if ($this->periode_selesai) {
            return 'Sampai '.$this->periode_selesai->format('d/m/Y');
        }

        return 'Permanent';
    }
}
