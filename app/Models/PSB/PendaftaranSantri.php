<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranSantri extends Model
{
    protected $table = 'psb_pendaftaran_santri';

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function waliSantri()
    {
        return $this->hasOne(WaliSantri::class, 'pendaftaran_santri_id');
    }

    public function jadwalWawancara()
    {
        return $this->hasOne(JadwalWawancara::class, 'santri_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'santri_id');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'santri_id');
    }

    public function ujianSantris()
    {
        return $this->hasMany(UjianSantri::class, 'santri_id');
    }

    public function jawabanSantris()
    {
        return $this->hasMany(JawabanSantri::class, 'santri_id');
    }
}