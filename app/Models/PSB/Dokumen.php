<?php

namespace App\Models\PSB;

use App\Models\PendaftaranSantri;
use App\Models\PSB\PendaftaranSantri as PSBPendaftaranSantri;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'psb_dokumen';

    protected $fillable = [
        'santri_id',
        'jenis_berkas',
        'file_path',
        'tanggal',
    ];

    public function santri()
    {
        return $this->belongsTo(PSBPendaftaranSantri::class, 'santri_id');
    }
}