<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IphDetailKomoditas extends Model
{
    use HasFactory;

    protected $table = 'iph_detail_komoditas';

    public $timestamps = false;

    protected $fillable = [
        'iph_mingguan_id',
        'komoditas_id',
        'nama_komoditas',
        'kategori_komoditas',
        'harga_minggu_ini',
        'harga_minggu_lalu',
        'perubahan_nominal',
        'perubahan_persen',
        'sumbangan_inflasi_persen',
        'sumbangan_inflasi_nominal',
        'jenis_dampak',
    ];

    protected function casts(): array
    {
        return [
            'harga_minggu_ini' => 'decimal:2',
            'harga_minggu_lalu' => 'decimal:2',
            'perubahan_nominal' => 'decimal:2',
            'perubahan_persen' => 'decimal:3',
            'sumbangan_inflasi_persen' => 'decimal:3',
            'sumbangan_inflasi_nominal' => 'decimal:4',
        ];
    }

    public function iphMingguan(): BelongsTo
    {
        return $this->belongsTo(IphMingguan::class);
    }

    public function komoditas(): BelongsTo
    {
        return $this->belongsTo(Komoditas::class);
    }
}
