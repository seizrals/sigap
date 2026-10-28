<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class IphBulanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'iph_bulanan';

    protected $fillable = [
        'tahun',
        'bulan',
        'nama_bulan',
        'iph_bulanan',
        'ranking',
        'komoditas_andil',
        'status_kategori',
        'catatan',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'bulan' => 'integer',
            'iph_bulanan' => 'decimal:4',
            'ranking' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

