<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasarLokasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pasar_lokasi';

    protected $fillable = [
        'kecamatan_id',
        'desa_id',
        'nama_lokasi',
        'tipe_lokasi',
        'alamat',
        'latitude',
        'longitude',
        'penanggung_jawab',
        'no_hp_pj',
        'jumlah_pedagang',
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
