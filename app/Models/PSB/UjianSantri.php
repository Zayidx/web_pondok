<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianSantri extends Model
{
    protected $table = 'psb_ujian_santris';

    public function pendaftaranSantri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }
}