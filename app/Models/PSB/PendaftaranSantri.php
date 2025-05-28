<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model for the psb_pendaftaran_santri table.
 *
 * @property int $id
 * @property string $nama_jenjang
 * @property string $nama_lengkap
 * @property string $nisn
 * @property string $tempat_lahir
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string $agama
 * @property string $email
 * @property string $no_whatsapp
 * @property string $asal_sekolah
 * @property string $tahun_lulus
 * @property string $status_santri
 * @property string $status_kesantrian
 * @property int $periode_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class PendaftaranSantri extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'psb_pendaftaran_santri';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_jenjang',
        'nama_lengkap',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'email',
        'no_whatsapp',
        'asal_sekolah',
        'tahun_lulus',
        'status_santri',
        'status_kesantrian',
        'periode_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'jenis_kelamin' => 'string',
        'agama' => 'string',
        'status_santri' => 'string',
        'status_kesantrian' => 'string',
    ];

    /**
     * Get the periode that owns this pendaftaran santri.
     *
     * @return BelongsTo
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    /**
     * Get the wali santri associated with this pendaftaran santri.
     *
     * @return HasOne
     */
    public function wali(): HasOne
    {
        return $this->hasOne(WaliSantri::class, 'pendaftaran_santri_id');
    }

    /**
     * Get the dokumen records associated with this pendaftaran santri.
     *
     * @return HasMany
     */
    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'santri_id');
    }
}