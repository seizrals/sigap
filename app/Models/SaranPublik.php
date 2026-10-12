<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaranPublik extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'saran_publik';

    protected $fillable = [
        'kode_laporan',
        'kategori',
        'sub_kategori',
        'nama_pelapor',
        'kontak_pelapor',
        'anonim',
        'kecamatan_id',
        'desa_id',
        'lokasi_spesifik',
        'latitude',
        'longitude',
        'judul_laporan',
        'isi_laporan',
        'harga_dilaporkan',
        'nama_komoditas_dilaporkan',
        'status',
        'divalidasi_oleh',
        'waktu_validasi',
        'catatan_validasi',
        'tindak_lanjut',
        'ditindaklanjuti_oleh',
        'waktu_tindak_lanjut',
        'foto_bukti_path',
        'menghasilkan_ews',
        'ews_alert_id',
    ];

    protected function casts(): array
    {
        return [
            'anonim' => 'boolean',
            'harga_dilaporkan' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'waktu_validasi' => 'datetime',
            'waktu_tindak_lanjut' => 'datetime',
            'menghasilkan_ews' => 'boolean',
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

    public function ewsAlert(): BelongsTo
    {
        return $this->belongsTo(EwsAlert::class);
    }
}
