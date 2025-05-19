<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianEssay extends Model
{
    protected $table = 'psb_penilaian_essays';

    public function jawabanSantri()
    {
        return $this->belongsTo(JawabanSantri::class, 'jawaban_santri_id');
    }
}