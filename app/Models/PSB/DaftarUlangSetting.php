<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;

class DaftarUlangSetting extends Model
{
    protected $table = 'psb_rekening_settings';
    
    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'catatan_transfer'
    ];
} 