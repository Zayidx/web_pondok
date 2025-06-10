<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;

class PeriodeDaftarUlang extends Model
{
    protected $table = 'psb_periode_daftar_ulang';
    
    protected $fillable = [
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'tahun_ajaran',
        'is_active'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean'
    ];
} 