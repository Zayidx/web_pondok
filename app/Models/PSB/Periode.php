<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model for the psb_periodes table.
 *
 * @property int $id
 * @property string $nama_periode
 * @property \Illuminate\Support\Carbon $periode_mulai
 * @property \Illuminate\Support\Carbon $periode_selesai
 * @property string $status_periode
 * @property string $tahun_ajaran
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Periode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'psb_periodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_periode',
        'periode_mulai',
        'periode_selesai',
        'status_periode',
        'tahun_ajaran',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'status_periode' => 'string',
    ];

    /**
     * Get the pendaftaran santri records associated with this periode.
     *
     * @return HasMany
     */
    public function pendaftaranSantri(): HasMany
    {
        return $this->hasMany(PendaftaranSantri::class, 'periode_id');
    }
}