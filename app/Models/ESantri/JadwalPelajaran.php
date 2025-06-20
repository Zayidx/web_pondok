<?php
namespace App\Models\ESantri;

use App\Models\Absensi\Absensi; // Pastikan Anda mengimpor model Absensi Anda
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';
    
    protected $fillable = [
        'kelas_id', 
        'kategori_pelajaran_id', 
        'mata_pelajaran', 
        'waktu_mulai', 
        'waktu_selesai', 
        'hari',
        'jenjang_id' 
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function kategoriPelajaran()
    {
        return $this->belongsTo(KategoriPelajaran::class);
    }

    // Tambahkan relasi ini
    public function absensi()
    {
        // Sesuaikan 'Absensi::class' dan 'foreign_key' jika nama model atau kolomnya berbeda
        // Misalnya, jika foreign key di tabel 'absensi' adalah 'jadwal_pelajaran_id'
        return $this->hasMany(Absensi::class, 'jadwal_pelajaran_id', 'id');
    }
}