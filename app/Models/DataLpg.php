<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataLpg extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'data_lpg';

    protected $fillable = [
        'tanggal_input',
        'kecamatan_id',
        'desa_id',
        'pangkalan_lpg_id',
        'user_id',
        'jenis_lpg',
        'stok_tersedia_tabung',
        'distribusi_masuk_tabung',
        'sumber_pemasok',
        'distribusi_keluar_tabung',
        'permintaan_harian_tabung',
        'harga_pangkalan',
        'harga_het_pemerintah',
        'harga_eceran_lapangan',
        'selisih_harga_eceran_het',
        'persentase_kenaikan_harga',
        'status_harga',
        'status_ketersediaan',
        'status_distribusi',
        'status_verifikasi',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
        'keterangan_kendala',
        'foto_bukti_path',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_input' => 'date',
            'harga_pangkalan' => 'decimal:2',
            'harga_het_pemerintah' => 'decimal:2',
            'harga_eceran_lapangan' => 'decimal:2',
            'selisih_harga_eceran_het' => 'decimal:2',
            'persentase_kenaikan_harga' => 'decimal:2',
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

    public function pangkalanLpg(): BelongsTo
    {
        return $this->belongsTo(PangkalanLpg::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
