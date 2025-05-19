<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'psb_periodes';

    public function pendaftaranSantri()
    {
        return $this->hasMany(PendaftaranSantri::class, 'periode_id');
    }
}