<?php

namespace App\Models\Absensi;

use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiDetail extends Model
{
    use HasFactory;
    protected $table = 'absensi_details';
    protected $fillable = ['absensi_id', 'santri_id', 'status', 'jam_hadir'];

    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}