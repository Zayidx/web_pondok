<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model for the psb_wali_santri table.
 *
 * @property int $id
 * @property int $pendaftaran_santri_id
 * @property string $nama_ayah
 * @property string $pekerjaan_ayah
 * @property string $pendidikan_ayah
 * @property string $penghasilan_ayah
 * @property string $nama_ibu
 * @property string $pekerjaan_ibu
 * @property string $pendidikan_ibu
 * @property string $no_telp_ibu
 * @property string $alamat
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class WaliSantri extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'psb_wali_santri';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pendaftaran_santri_id',
        'nama_wali',
        'nama_ayah',
        'pekerjaan_ayah',
        'pendidikan_ayah',
        'penghasilan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'pendidikan_ibu',
        'no_telp_ibu',
        'alamat',
        'hubungan',
        'pekerjaan',
        'no_hp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'pendidikan_ayah' => 'string',
        'penghasilan_ayah' => 'string',
        'pendidikan_ibu' => 'string',
    ];

    /**
     * Get the pendaftaran santri that owns this wali santri.
     *
     * @return BelongsTo
     */
    public function pendaftaranSantri(): BelongsTo
    {
        return $this->belongsTo(PendaftaranSantri::class, 'pendaftaran_santri_id');
    }
}