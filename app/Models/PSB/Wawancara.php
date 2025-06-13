<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wawancara extends Model
{
    use HasFactory;

    protected $table = 'wawancara';

    protected $fillable = [
        'santri_id',
        'tanggal_wawancara',
        'jam_wawancara',
        'mode_wawancara',
        'link_meeting',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal_wawancara' => 'date',
        'jam_wawancara' => 'datetime',
    ];

    public function santri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }
} 