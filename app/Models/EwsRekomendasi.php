<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EwsRekomendasi extends Model
{
    use HasFactory;

    protected $table = 'ews_rekomendasi';

    protected $fillable = [
        'ews_alert_id',
        'tipe_rekomendasi',
        'judul_rekomendasi',
        'detail_rekomendasi',
        'target_pelaksana',
        'estimasi_waktu_pelaksanaan_jam',
        'estimasi_biaya',
        'status_rekomendasi',
        'disetujui_oleh',
        'waktu_persetujuan',
        'catatan_hasil',
        'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'estimasi_biaya' => 'decimal:2',
            'waktu_persetujuan' => 'datetime',
        ];
    }

    public function ewsAlert(): BelongsTo
    {
        return $this->belongsTo(EwsAlert::class);
    }
}
