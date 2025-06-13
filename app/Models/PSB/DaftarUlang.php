<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarUlang extends Model
{
    use HasFactory;

    protected $table = 'daftar_ulang';

    protected $fillable = [
        'santri_id',
        'nominal_pembayaran',
        'tanggal_pembayaran',
        'bank_pengirim',
        'nama_pengirim',
        'bukti_pembayaran',
        'status_pembayaran',
        'catatan_verifikasi',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'nominal_pembayaran' => 'decimal:2',
    ];

    public function santri()
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }
} 