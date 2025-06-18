<?php

namespace App\Models\Absensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Santri;
use App\Models\ESantri\JadwalPelajaran;

class QrToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'jadwal_pelajaran_id',
        'used_by',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'used_by');
    }
}