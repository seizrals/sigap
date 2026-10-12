<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EwsAlert extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ews_alerts';

    protected $fillable = [
        'kode_alert',
        'sumber_deteksi',
        'kategori',
        'tingkat_risiko',
        'status',
        'kecamatan_id',
        'desa_id',
        'komoditas_id',
        'pangkalan_lpg_id',
        'spbu_id',
        'judul_alert',
        'analisis_sistem',
        'analisis_penyebab',
        'data_pemicu',
        'nilai_terukur',
        'nilai_indikasi_harga_persen',
        'nilai_indikasi_stok_persen',
        'lama_zonasi_berurutan_hari',
        'waktu_terdeteksi',
        'waktu_terakhir_update',
        'dibuat_oleh',
        'ditangani_oleh',
        'waktu_penanganan',
        'tindak_lanjut',
        'catatan_penanganan',
        'bukti_penanganan_path',
    ];

    protected function casts(): array
    {
        return [
            'data_pemicu' => 'array',
            'nilai_terukur' => 'array',
            'nilai_indikasi_harga_persen' => 'decimal:2',
            'nilai_indikasi_stok_persen' => 'decimal:2',
            'waktu_terdeteksi' => 'datetime',
            'waktu_terakhir_update' => 'datetime',
            'waktu_penanganan' => 'datetime',
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

    public function komoditas(): BelongsTo
    {
        return $this->belongsTo(Komoditas::class);
    }

    public function pangkalanLpg(): BelongsTo
    {
        return $this->belongsTo(PangkalanLpg::class);
    }

    public function spbu(): BelongsTo
    {
        return $this->belongsTo(Spbu::class);
    }

    public function rekomendasi(): HasMany
    {
        return $this->hasMany(EwsRekomendasi::class);
    }
}
