<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'psb_periodes';

    protected $fillable = [
        'nama_jenjang',
        'periode_mulai',
        'periode_selesai',
        'status_periode',
    ];

    public function santri()
    {
        return $this->hasMany(PendaftaranSantri::class, 'periode_id');
    }
}