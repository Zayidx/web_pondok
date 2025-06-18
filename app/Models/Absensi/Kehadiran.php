<?php

namespace App\Models\Absensi;

use App\Models\Santri;
use App\Models\ESantri\JadwalPelajaran; // Pastikan namespace ini benar
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     * Baris inilah yang akan memperbaiki error Anda.
     */
    protected $table = 'kehadiran';

    protected $fillable = [
        'santri_id',
        'jadwal_pelajaran_id',
        'tanggal',
        'status',
        'waktu_hadir'
    ];

    protected $casts = [
        'waktu_hadir' => 'datetime',
        'tanggal' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function jadwalPelajaran()
    {
        // Pastikan model JadwalPelajaran ada di namespace ini
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_pelajaran_id');
    }
}