<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'psb_dokumen';

    public function pendaftaranSantri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }

    public function berkasPendaftaran()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'berkas_pendaftaran_id');
    }
}