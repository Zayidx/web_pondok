<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaliSantri extends Model
{
    protected $table = 'psb_wali_santri';

    public function pendaftaranSantri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'pendaftaran_santri_id');
    }
}