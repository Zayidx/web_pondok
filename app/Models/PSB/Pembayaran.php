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
        'jumlah',
        'tanggal_bayar',
        'bukti_transfer',
        'status_pembayaran',
    ];

    public function santri()
    {
        return $this->belongsTo(PSBPendaftaranSantri::class, 'santri_id');
    }
}