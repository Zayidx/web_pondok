<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'psb_pembayaran';

    public function pendaftaranSantri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }
}