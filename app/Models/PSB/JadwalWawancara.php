<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;

class JadwalWawancara extends Model
{
    protected $table = 'psb_jadwal_wawancara';
    protected $fillable = [
        'santri_id',
        'tanggal_wawancara',
        'jam_wawancara',
        'mode',
        'link_online',
        'lokasi_offline',
    ];

    public function santri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }
}