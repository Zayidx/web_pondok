<?php
namespace App\Models\ESantri;

use Illuminate\Database\Eloquent\Model;

class KategoriPelajaran extends Model
{
    protected $table = 'kategori_pelajaran';
    
    protected $fillable = [
        'nama',
        'deskripsi',
        'role_guru', // Jika kolom ini tidak lagi relevan dengan guru, pertimbangkan untuk menghapusnya dari tabel database juga.
    ];

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }
}
