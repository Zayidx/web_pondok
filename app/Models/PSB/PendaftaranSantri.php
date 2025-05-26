<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranSantri extends Model
{
    use HasFactory;

    protected $table = 'psb_pendaftaran_santri';

    protected $fillable = [
        'nama_jenjang',
        'nama_lengkap',
        'nisn',
        'nism',
        'npsn',
        'kip',
        'no_kk',
        'jumlah_saudara_kandung',
        'anak_keberapa',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'asal_sekolah',
        'no_whatsapp',
        'email',
        'status_santri',
        'kewarganegaraan',
        'kelas',
        'pembiayaan',
        'riwayat_penyakit',
        'hobi',
        'aktivitas_pendidikan',
        'nik',
        'status_kesantrian',
        'periode_id',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function wali()
    {
        return $this->hasOne(WaliSantri::class, 'pendaftaran_santri_id');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'santri_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'santri_id');
    }

    public function jadwalWawancara()
    {
        return $this->hasOne(JadwalWawancara::class, 'santri_id');
    }
}