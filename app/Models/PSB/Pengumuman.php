<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'psb_pengumuman';

    protected $fillable = [
        'santri_id',
        'tanggal_pengumuman',
        'jam_pengumuman',
        'status',
        'catatan',
        'file_pengumuman',
    ];

    protected $casts = [
        'tanggal_pengumuman' => 'date',
        'jam_pengumuman' => 'datetime',
    ];

    public function santri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }
} 