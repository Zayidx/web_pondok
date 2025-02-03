<?php
namespace App\Models\ESantri;

use App\Models\Jenjang;
use App\Models\Kelas;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';

    protected $fillable = [
        'jenjang_id',
        'kelas_id',
        'kategori_pelajaran_id',
        'mata_pelajaran',
        'waktu_mulai',
        'waktu_selesai',
        'hari',
        'role_guru'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }

    public function kategoriPelajaran()
    {
        return $this->belongsTo(kategoriPelajaran::class);
    }
}
