<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Komoditas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_komoditas',
        'nama_komoditas',
        'kategori',
        'satuan',
        'harga_het',
        'harga_rata_rata_kabupaten',
        'stok_aman_minimum',
        'batas_atas_harga_normal',
        'batas_bawah_harga_normal',
        'persentase_kenaikan_waspada',
        'persentase_kenaikan_bahaya',
        'is_strategis',
        'is_active',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'is_strategis' => 'boolean',
            'is_active' => 'boolean',
            'harga_het' => 'decimal:2',
            'harga_rata_rata_kabupaten' => 'decimal:2',
        ];
    }
}
