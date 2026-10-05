<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kecamatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kecamatan';

    protected $fillable = [
        'kode_kecamatan',
        'nama_kecamatan',
        'latitude',
        'longitude',
        'jumlah_desa',
        'jumlah_penduduk',
        'zona_risiko',
        'catatan_geografis',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function desas(): HasMany
    {
        return $this->hasMany(Desa::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
