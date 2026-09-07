<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'metode',
        'nominal',
        'tanggal_bayar',
        'status',
        'bukti_transfer',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'tanggal_bayar' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Pesanan, $this>
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    /**
     * Scope a query to only include valid revenue payments.
     *
     * @param  Builder<Pembayaran>  $query
     * @return Builder<Pembayaran>
     */
    public function scopeValidRevenue($query)
    {
        return $query->where('status', 'berhasil')
            ->whereHas('pesanan', function ($q) {
                $q->where('status_pesanan', '!=', 'dibatalkan');
            });
    }

    /**
     * Get payment settings from JSON file
     */
    public static function getSettings(): array
    {
        if (! Storage::exists('payment_settings.json')) {
            $settings = [
                'qris' => [
                    'status' => true,
                    'merchant_id' => 'KOTE-SHOP-001',
                    'merchant_name' => 'KOTE SCHOOL SHOP OFFICIAL',
                    'qr_image' => 'images/QR Pembayaran.jpeg',
                ],
                'transfer_bank' => [
                    'status' => true,
                    'bank_name' => 'BCA',
                    'no_rekening' => '7735091822',
                    'nama_pemilik' => 'Kote School Shop',
                ],
                'tunai' => [
                    'status' => true,
                ],
            ];
            Storage::put('payment_settings.json', json_encode($settings, JSON_PRETTY_PRINT));

            return $settings;
        }

        return json_decode(Storage::get('payment_settings.json'), true);
    }

    /**
     * Save payment settings to JSON file
     */
    public static function saveSettings(array $settings): void
    {
        Storage::put('payment_settings.json', json_encode($settings, JSON_PRETTY_PRINT));
    }
}
