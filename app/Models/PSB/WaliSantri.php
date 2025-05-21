<?php

namespace App\Models\PSB;

use App\Models\PendaftaranSantri;
use App\Models\PSB\PendaftaranSantri as PSBPendaftaranSantri;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliSantri extends Model
{
    use HasFactory;

    protected $table = 'psb_wali_santri';

    protected $fillable = [
        'pendaftaran_santri_id',
        'nama_kepala_keluarga',
        'no_hp_kepala_keluarga',
        'nama_ayah',
        'status_ayah',
        'kewarganegaraan_ayah',
        'nik_ayah',
        'tempat_lahir_ayah',
        'tanggal_lahir_ayah',
        'pendidikan_terakhir_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'no_telp_ayah',
        'nama_ibu',
        'status_ibu',
        'kewarganegaraan_ibu',
        'nik_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'pendidikan_terakhir_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'no_telp_ibu',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'rt',
        'rw',
        'kode_pos',
        'status_kepemilikan_rumah',
        'alamat',
        'status_orang_tua',
    ];

    public function santri()
    {
        return $this->belongsTo(PSBPendaftaranSantri::class, 'pendaftaran_santri_id');
    }
}