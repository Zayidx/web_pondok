<?php

namespace App\Models;

use App\Models\ESantri\JadwalPelajaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'absensi';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'tanggal',
        'kelas_id',
        'jadwal_pelajaran_id',
    ];

    /**
     * Relasi one-to-many: Satu sesi Absensi memiliki banyak AbsensiDetail.
     */
    public function details()
    {
        return $this->hasMany(AbsensiDetail::class);
    }

    /**
     * Relasi one-to-one: Satu sesi Absensi memiliki satu QrSession.
     */
    public function qrSession()
    {
        return $this->hasOne(QrSession::class);
    }

    /**
     * Relasi belongs-to: Satu sesi Absensi milik satu Kelas.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relasi belongs-to: Satu sesi Absensi milik satu JadwalPelajaran.
     */
    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }
}
