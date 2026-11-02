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
        'nama_pengelola',
        'no_hp',
        'jenis_bbm_tersedia',
        'kapasitas_tanki_pertalite_kl',
        'kapasitas_tanki_pertamax_kl',
        'kapasitas_tanki_solar_kl',
        'kapasitas_tanki_dexlite_kl',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
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
