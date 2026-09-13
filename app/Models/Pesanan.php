<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $primaryKey = 'id_pesanan';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'tanggal_pesan',
        'total_harga',
        'metode_pembayaran',
        'tipe_pesanan',
        'alamat_pengiriman',
        'status_pesanan',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pesan' => 'datetime',
            'total_harga' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * @return HasMany<DetailPesanan, $this>
     */
    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    /**
     * @return HasOne<Pembayaran, $this>
     */
    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    /**
     * @return HasOne<Ulasan, $this>
     */
    public function ulasan(): HasOne
    {
        return $this->hasOne(Ulasan::class, 'id_pesanan', 'id_pesanan');
    }

    /**
     * Scope a query to only include valid revenue orders.
     * Valid revenue orders must have successful/verified payment and not be cancelled.
     *
     * @param  Builder<Pesanan>  $query
     * @return Builder<Pesanan>
     */
    public function scopeValidRevenue($query)
    {
        return $query->where('status_pesanan', '!=', 'dibatalkan')
            ->whereHas('pembayaran', function ($q) {
                $q->where('status', 'berhasil');
            });
    }

    /**
     * Generate order number format: #ORD-YYYYMMDD-XXX
     */
    public function getOrderNumberAttribute(): string
    {
        $date = $this->tanggal_pesan->format('Ymd');
        $sequence = str_pad($this->id_pesanan, 3, '0', STR_PAD_LEFT);

        return "#ORD-{$date}-{$sequence}";
    }

    /**
     * Get a summary string of all items in this order.
     */
    public function getItemsSummaryAttribute(): string
    {
        return $this->detailPesanan
            ->map(fn (DetailPesanan $detail) => "{$detail->jumlah}x {$detail->menu->nama_menu}")
            ->implode(', ');
    }

    /**
     * Get combined notes from order level and item level notes.
     */
    public function getCatatanLengkapAttribute(): ?string
    {
        $notes = [];

        if (! empty($this->catatan)) {
            $notes[] = $this->catatan;
        }

        foreach ($this->detailPesanan as $detail) {
            if (! empty($detail->catatan)) {
                $notes[] = "{$detail->menu?->nama_menu}: {$detail->catatan}";
            }
        }

        return ! empty($notes) ? implode(' | ', $notes) : null;
    }

    /**
     * Get human-readable label for tipe pesanan.
     */
    public function getTipePesananLabelAttribute(): string
    {
        return $this->tipe_pesanan === 'diantar' ? 'Diantar' : 'Ambil di Toko';
    }

    /**
     * Check if order is delivery.
     */
    public function isDiantar(): bool
    {
        return $this->tipe_pesanan === 'diantar';
    }

    /**
     * Get the most ordered item in this order (highest quantity).
     */
    public function getTopDetailAttribute(): ?DetailPesanan
    {
        return $this->detailPesanan->sortByDesc('jumlah')->first();
    }

    /**
     * Get the menu of the most ordered item.
     */
    public function getTopMenuAttribute(): ?Menu
    {
        return $this->top_detail?->menu;
    }

    /**
     * Get the image of the most ordered item.
     */
    public function getTopMenuImageAttribute(): ?string
    {
        return $this->top_menu?->gambar;
    }
}
