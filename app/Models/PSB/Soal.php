<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'psb_soals';

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function pilihanJawabanSoals()
    {
        return $this->hasMany(PilihanJawabanSoal::class, 'soal_id');
    }
}