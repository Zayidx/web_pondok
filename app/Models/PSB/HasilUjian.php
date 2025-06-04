<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    use HasFactory;

    protected $table = 'hasil_ujians';
    protected $fillable = [
        'santri_id',
        'ujian_id',
        'total_skor',
        'status',
    ];

    public function santri()
    {
        return $this->belongsTo(\App\Models\PSB\PendaftaranSantri::class, 'santri_id');
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}