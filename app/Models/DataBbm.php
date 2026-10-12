<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataBbm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'data_bbm';

    protected $fillable = [
        'tanggal_input',
        'kecamatan_id',
        'spbu_id',
        'user_id',
        'jenis_bbm',
        'stok_tersedia_kl',
        'stok_masuk_hari_ini_kl',
        'penjualan_harian_rata_kl',
        'estimasi_hari_ketersediaan',
        'distribusi_nelayan',
        'kuota_nelayan_harian_kl',
        'realisasi_nelayan_kl',
        'distribusi_pertanian',
        'kuota_pertanian_harian_kl',
        'realisasi_pertanian_kl',
        'status_antrean',
        'estimasi_waktu_tunggu_menit',
        'status_ketersediaan',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
        'harga_jual_per_liter',
        'keterangan_kendala',
        'foto_bukti_path',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_input' => 'date',
            'stok_tersedia_kl' => 'decimal:3',
            'stok_masuk_hari_ini_kl' => 'decimal:3',
            'penjualan_harian_rata_kl' => 'decimal:3',
            'estimasi_hari_ketersediaan' => 'decimal:2',
            'distribusi_nelayan' => 'boolean',
            'kuota_nelayan_harian_kl' => 'decimal:3',
            'realisasi_nelayan_kl' => 'decimal:3',
            'distribusi_pertanian' => 'boolean',
            'kuota_pertanian_harian_kl' => 'decimal:3',
            'realisasi_pertanian_kl' => 'decimal:3',
            'harga_jual_per_liter' => 'decimal:2',
            'verified_at' => 'datetime',
            'foto_bukti_path' => 'array',
        ];
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function spbu(): BelongsTo
    {
        return $this->belongsTo(Spbu::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
