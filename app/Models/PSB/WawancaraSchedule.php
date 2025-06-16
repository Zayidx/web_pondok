<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WawancaraSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
        'tanggal',
        'waktu',
        'status',
        'ruangan',
        'pewawancara',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }
} 
 
 
 
 
 