<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringTrigger extends Model
{
    use HasFactory;

    protected $table = 'monitoring_triggers';

    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'kecamatan_id',
        'pemicu_harga',
        'pemicu_pangan',
        'pemicu_lpg',
        'pemicu_bbm',
        'total_pemicu_terdeteksi',
        'status_komposit',
        'waktu_update_terakhir',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'pemicu_harga' => 'array',
            'pemicu_pangan' => 'array',
            'pemicu_lpg' => 'array',
            'pemicu_bbm' => 'array',
            'waktu_update_terakhir' => 'datetime',
        ];
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
