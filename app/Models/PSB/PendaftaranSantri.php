<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
 * @property string $tipe_pendaftaran
 * @property \Illuminate\Support\Carbon|null $tanggal_wawancara
 * @property string|null $jam_wawancara
 * @property string|null $mode
 * @property string|null $link_online
 * @property string|null $lokasi_offline
 * @property string|null $reason_rejected
 * @property int $periode_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class PendaftaranSantri extends Authenticatable
{
    use HasFactory, Notifiable;

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
        'tipe_pendaftaran',
        'tanggal_wawancara',
        'jam_wawancara',
        'mode',
        'link_online',
        'lokasi_offline',
        'reason_rejected',
        'periode_id',
        'password',
        'nominal_pembayaran',
        'tanggal_pembayaran',
        'bank_pengirim',
        'nama_pengirim',
        'bukti_pembayaran',
        'status_pembayaran',
        'catatan_verifikasi',
    'rata_rata_ujian',         // <-- Pastikan ini ada!
    'total_nilai_semua_ujian'  // <-- Pastikan ini ada!
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_wawancara' => 'date',
        'tanggal_pembayaran' => 'date',
        'jenis_kelamin' => 'string',
        'agama' => 'string',
        'status_santri' => 'string',
        'status_kesantrian' => 'string',
        'tipe_pendaftaran' => 'string',
        'mode' => 'string',
        'nominal_pembayaran' => 'decimal:2',
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

    /**
     * Get the jawaban santri records associated with this pendaftaran santri.
     *
     * @return HasMany
     */
    public function jawabanSantris(): HasMany
    {
        return $this->hasMany(\App\Models\JawabanSantri::class, 'santri_id');
    }

    /**
     * Get the hasil ujian records associated with this pendaftaran santri.
     *
     * @return HasMany
     */
    public function hasilUjians(): HasMany
    {
        return $this->hasMany(\App\Models\PSB\HasilUjian::class, 'santri_id');
    }

    /**
     * Get the jawaban ujian records associated with this pendaftaran santri through hasil ujians.
     *
     * @return HasManyThrough
     */
    public function jawabanUjians(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\PSB\JawabanUjian::class,
            \App\Models\PSB\HasilUjian::class,
            'santri_id',
            'hasil_ujian_id'
        );
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}