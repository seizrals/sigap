<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PangkalanLpg extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pangkalan_lpg';

    protected $fillable = [
        'kecamatan_id',
        'desa_id',
        'kode_pangkalan',
        'nama_pangkalan',
        'nama_pemilik',
        'no_hp',
        'alamat',
        'latitude',
        'longitude',
        'no_izin_usaha',
        'status_izin',
        'kapasitas_stok_maks',
        'is_active',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
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
