<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'kategori',
        'keterangan',
        'jumlah',
        'tanggal',
        'metode_pembayaran',
        'bukti_transaksi',
        'id_user',
    ];

    public const KATEGORI_LIST = [
        'Bahan Baku' => [
            'label' => 'Bahan Baku',
            'color' => '#10b981',
            'icon' => 'cube',
            'description' => 'Biji kopi, susu, sirup, teh, bahan makanan/pastry',
        ],
        'Operasional' => [
            'label' => 'Operasional',
            'color' => '#3b82f6',
            'icon' => 'cog',
            'description' => 'Cup takeaway, sedotan, plastik, kebersihan & seragam',
        ],
        'Peralatan' => [
            'label' => 'Peralatan',
            'color' => '#f59e0b',
            'icon' => 'wrench',
            'description' => 'Perawatan mesin espresso, grinder, blender, utensil',
        ],
        'Listrik & Internet' => [
            'label' => 'Listrik & Internet',
            'color' => '#8b5cf6',
            'icon' => 'lightning',
            'description' => 'Tagihan PLN, WiFi toko, air PDAM',
        ],
        'Lainnya' => [
            'label' => 'Lainnya',
            'color' => '#6b7280',
            'icon' => 'dots',
            'description' => 'Promosi/iklan, sewa tempat, administrasi & biaya tak terduga',
        ],
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah' => 'decimal:2',
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
     * Formatted Rupiah Amount
     */
    public function getFormattedJumlahAttribute(): string
    {
        return 'Rp '.number_format((float) $this->jumlah, 0, ',', '.');
    }
}
