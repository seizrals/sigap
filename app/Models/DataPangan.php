<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataPangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'data_pangan';

    protected $fillable = [
        'tanggal_input',
        'kecamatan_id',
        'desa_id',
        'pasar_lokasi_id',
        'komoditas_id',
        'user_id',
        'harga_hari_ini',
        'harga_hari_sebelumnya',
        'perubahan_harga_nominal',
        'perubahan_harga_persen',
        'stok_tersedia',
        'satuan_stok',
        'stok_masuk_hari_ini',
        'sumber_distribusi_masuk',
        'stok_keluar_hari_ini',
        'status_harga',
        'status_ketersediaan',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
        'keterangan_lapangan',
        'foto_bukti_path',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_input' => 'date',
            'harga_hari_ini' => 'decimal:2',
            'harga_hari_sebelumnya' => 'decimal:2',
            'perubahan_harga_nominal' => 'decimal:2',
            'perubahan_harga_persen' => 'decimal:2',
            'stok_tersedia' => 'decimal:2',
            'stok_masuk_hari_ini' => 'decimal:2',
            'stok_keluar_hari_ini' => 'decimal:2',
            'verified_at' => 'datetime',
            'foto_bukti_path' => 'array',
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

    public function pasarLokasi(): BelongsTo
    {
        return $this->belongsTo(PasarLokasi::class);
    }

    public function komoditas(): BelongsTo
    {
        return $this->belongsTo(Komoditas::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
