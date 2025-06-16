<?php

namespace App\Models\PSB;

use App\Models\PendaftaranSantri;
use App\Models\PSB\PendaftaranSantri as PSBPendaftaranSantri;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'psb_pembayaran';

    protected $fillable = [
        'santri_id',
        'nominal',
        'nama_pengirim',
        'bank_pengirim',
        'tanggal_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
    ];

    public function santri()
    {
        return $this->belongsTo(PSBPendaftaranSantri::class, 'santri_id');
    }
}