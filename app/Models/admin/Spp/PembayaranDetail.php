<?php

namespace App\Models\Admin\Spp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranDetail extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_detail'; // pastikan nama tabel sesuai di database

    protected $fillable = [
        'nama',
        'nominal',
        'jenjang_id',
        'pembayaran_tipe_id',
        'tahun_ajaran_id',
    ];

    public function jenjang()
    {
        return $this->belongsTo(\App\Models\Jenjang::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(\App\Models\TahunAjaran::class);
    }
}
