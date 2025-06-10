<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;

class RekeningSettings extends Model
{
    protected $table = 'psb_rekening_settings';
    
    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
} 