<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilihanJawabanSoal extends Model
{
    protected $table = 'psb_pilihan_jawaban_soals';

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}