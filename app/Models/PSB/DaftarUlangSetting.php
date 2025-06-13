<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;

class DaftarUlangSetting extends Model
{
    protected $table = 'daftar_ulang_settings';
    
    protected $fillable = [
        'bank',
        'nomor_rekening',
        'atas_nama',
        'catatan_transfer'
    ];
} 