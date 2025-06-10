<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;

class RincianBiaya extends Model
{
    protected $table = 'psb_rincian_biaya';
    
    protected $fillable = [
        'nama_biaya',
        'jumlah',
        'tahun_ajaran',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'is_active' => 'boolean'
    ];
} 