<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSantri extends Model
{
    protected $table = 'psb_jawaban_santris';

    public function pendaftaranSantri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }

    public function pilihanJawabanSoal()
    {
        return $this->belongsTo(PilihanJawabanSoal::class, 'jawaban_pg_id');
    }

    public function penilaianEssays()
    {
        return $this->hasOne(PenilaianEssay::class, 'jawaban_santri_id');
    }
}