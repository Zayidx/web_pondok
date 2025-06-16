<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSantri extends Model
{
    use HasFactory;

    protected $table = 'jawaban_santris';
    protected $fillable = [
        'santri_id',
        'ujian_id',
        'soal_id',
        'jawaban',
        'skor',
        'catatan',
    ];

    public function santri()
    {
        return $this->belongsTo(\App\Models\PSB\PendaftaranSantri::class, 'santri_id');
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}