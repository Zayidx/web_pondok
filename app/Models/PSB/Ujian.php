<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $table = 'psb_ujians';

    public function soals()
    {
        return $this->hasMany(Soal::class, 'ujian_id');
    }

    public function ujianSantris()
    {
        return $this->hasMany(UjianSantri::class, 'ujian_id');
    }

    public function jawabanSantris()
    {
        return $this->hasMany(JawabanSantri::class, 'ujian_id');
    }
}