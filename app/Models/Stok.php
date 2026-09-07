<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';

    protected $primaryKey = 'id_stok';

    /**
     * Disable Laravel's default created_at & updated_at timestamps.
     */
    public $timestamps = false;

    protected $fillable = [
        'id_menu',
        'stok_masuk',
        'stok_keluar',
        'stok_tersedia',
        'tanggal_update',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'stok_masuk' => 'integer',
            'stok_keluar' => 'integer',
            'stok_tersedia' => 'integer',
            'tanggal_update' => 'datetime',
        ];
    }

    /**
     * Relasi ke model Menu
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}
