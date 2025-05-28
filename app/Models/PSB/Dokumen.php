<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model for the psb_dokumen table.
 *
 * @property int $id
 * @property int $santri_id
 * @property string $jenis_berkas
 * @property string $file_path
 * @property \Illuminate\Support\Carbon $tanggal
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Dokumen extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'psb_dokumen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'santri_id',
        'jenis_berkas',
        'file_path',
        'tanggal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the pendaftaran santri that owns this dokumen.
     *
     * @return BelongsTo
     */
    public function pendaftaranSantri(): BelongsTo
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }
}