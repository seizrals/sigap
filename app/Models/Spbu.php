<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spbu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spbu';

    protected $fillable = [
        'kecamatan_id',
        'desa_id',
        'kode_spbu',
        'nama_spbu',
        'tipe_spbu',
        'nama_pengelola',
        'no_hp',
        'alamat',
        'latitude',
        'longitude',
        'jenis_bbm_tersedia',
        'kapasitas_tanki_pertalite_kl',
        'kapasitas_tanki_pertamax_kl',
        'kapasitas_tanki_solar_kl',
        'kapasitas_tanki_dexlite_kl',
        'melayani_nelayan',
        'melayani_pertanian',
        'is_active',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'melayani_nelayan' => 'boolean',
            'melayani_pertanian' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'jenis_bbm_tersedia' => 'array',
        ];
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
}
