<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    protected $table = 'ujians';

    protected $fillable = [
        'nama_ujian',
        'mata_pelajaran',
        'periode_id',
        'tanggal_ujian',
        'waktu_mulai',
        'waktu_selesai',
        'status_ujian',
    ];

    protected $casts = [
        'tanggal_ujian' => 'date',
        'status_ujian' => 'string',
    ];

    public function periode()
    {
        return $this->belongsTo(\App\Models\PSB\Periode::class, 'periode_id');
    }

    public function soals()
    {
        return $this->hasMany(Soal::class, 'ujian_id');
    }
}