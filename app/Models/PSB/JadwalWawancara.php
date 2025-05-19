<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalWawancara extends Model
{
    protected $table = 'psb_jadwal_wawancara';

    public function pendaftaranSantri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }
}