<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiDetail extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'absensi_details';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'absensi_id',
        'santri_id',
        'status',
        'jam_hadir',
    ];

    /**
     * Relasi belongs-to: Satu AbsensiDetail milik satu Absensi (sesi induk).
     */
    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }

    /**
     * Relasi belongs-to: Satu AbsensiDetail milik satu Santri.
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
