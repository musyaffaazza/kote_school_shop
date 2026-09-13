<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id_user';
    }

    /**
     * Disable Laravel's default updated_at; only created_at exists in migration.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'nis',
        'foto_identitas',
        'kelas',
        'jenis_kelamin',
        'role',
        'jabatan',
        'alamat',
        'cart',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'cart' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Pesanan, $this>
     */
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_user', 'id_user');
    }

    /**
     * Get formatted customer ID (e.g. #KT-USR-0104).
     */
    public function getFormattedIdAttribute(): string
    {
        return '#KT-USR-'.str_pad((string) $this->id_user, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get URL for identity photo (KTP/Kartu Pelajar).
     */
    public function getFotoIdentitasUrlAttribute(): ?string
    {
        if (empty($this->foto_identitas)) {
            return null;
        }

        if (str_starts_with($this->foto_identitas, 'http://') || str_starts_with($this->foto_identitas, 'https://')) {
            return $this->foto_identitas;
        }

        $cleanPath = ltrim(str_replace('/storage/', '', $this->foto_identitas), '/');
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/'.$cleanPath);
        }

        return null;
    }

    /**
     * Get gender display label.
     */
    public function getJenisKelaminLabelAttribute(): string
    {
        return match ($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => '-',
        };
    }
}
