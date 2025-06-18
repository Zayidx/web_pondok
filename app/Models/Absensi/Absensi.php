<?php

namespace App\Models\Absensi;

use App\Models\ESantri\JadwalPelajaran;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $table = 'absensi';
    protected $fillable = ['jadwal_pelajaran_id', 'kelas_id', 'tanggal'];

    public function details()
    {
        return $this->hasMany(AbsensiDetail::class);
    }

    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}