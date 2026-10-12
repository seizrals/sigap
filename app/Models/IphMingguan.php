<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class IphMingguan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'iph_mingguan';

    protected $fillable = [
        'tahun',
        'bulan',
        'minggu_ke',
        'periode_label',
        'tanggal_awal_periode',
        'tanggal_akhir_periode',
        'tanggal_publikasi',
        'user_id',
        'sumber_data',
        'iph_kabupaten',
        'iph_sebelumnya',
        'perubahan_iph_nominal',
        'perubahan_iph_persen',
        'inflasi_minggu_ini_persen',
        'inflasi_ytd_persen',
        'inflasi_yoy_persen',
        'analisis_ringkas',
        'komoditas_penyumbang_inflasi',
        'komoditas_penahan_inflasi',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
        'metadata_grafik',
        'lampiran_dokumen_path',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_awal_periode' => 'date',
            'tanggal_akhir_periode' => 'date',
            'tanggal_publikasi' => 'date',
            'iph_kabupaten' => 'decimal:4',
            'iph_sebelumnya' => 'decimal:4',
            'perubahan_iph_nominal' => 'decimal:4',
            'perubahan_iph_persen' => 'decimal:3',
            'inflasi_minggu_ini_persen' => 'decimal:3',
            'inflasi_ytd_persen' => 'decimal:3',
            'inflasi_yoy_persen' => 'decimal:3',
            'verified_at' => 'datetime',
            'metadata_grafik' => 'array',
            'lampiran_dokumen_path' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailKomoditas(): HasMany
    {
        return $this->hasMany(IphDetailKomoditas::class);
    }
}
