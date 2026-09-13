<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $primaryKey = 'id_ulasan';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_pesanan',
        'id_menu',
        'rating',
        'komentar',
        'tanggal_ulasan',
        'balasan',
        'tanggal_balasan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'tanggal_ulasan' => 'datetime',
            'tanggal_balasan' => 'datetime',
        ];
    }

    /**
     * Cek apakah ulasan sudah dibalas oleh admin.
     */
    public function hasBalasan(): bool
    {
        return ! empty($this->balasan);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * @return BelongsTo<Pesanan, $this>
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    /**
     * @return BelongsTo<Menu, $this>
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}
