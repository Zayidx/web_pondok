<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;

class SertifikatTemplate extends Model
{
    protected $table = 'psb_sertifikat_templates';
    
    protected $fillable = [
        'nama_pesantren',
        'nama_yayasan',
        'alamat_pesantren',
        'nomor_telepon',
        'email_pesantren',
        'catatan_penting',
        'nama_direktur',
        'nip_direktur',
        'nama_kepala_admin',
        'nip_kepala_admin',
    ];

    protected $casts = [
        'catatan_penting' => 'array',
    ];
} 