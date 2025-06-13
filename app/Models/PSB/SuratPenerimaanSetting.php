<?php

namespace App\Models\PSB;

use Database\Seeders\SuratPenerimaanSettingSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Pastikan ini ada jika Anda menggunakan factory

class SuratPenerimaanSetting extends Model
{
    // Menggunakan trait HasFactory untuk pabrik model
    use HasFactory;

    // Menentukan nama tabel eksplisit. Ini HARUS sesuai dengan nama tabel di migrasi (psb_sertifikat_templates).
    protected $table = 'psb_sertifikat_templates';

    // Mendefinisikan atribut yang dapat diisi secara massal (mass assignable).
    // Kolom-kolom ini HARUS ada dalam daftar $fillable agar dapat diisi melalui
    // metode seperti Model::create() atau Model::update().
    protected $fillable = [
        'nama_pesantren',
        'nama_yayasan',
        'alamat_pesantren',
        'telepon_pesantren', // Diubah dari 'nomor_telepon' agar sesuai dengan migrasi
        'email_pesantren',
        'catatan_penting',
        'nama_direktur',
        'nip_direktur',
        'nama_kepala_admin',
        'nip_kepala_admin',
        'tahun_ajaran',         // Tambahkan kolom ini jika ada di seeder/migration
        'tanggal_orientasi',    // Tambahkan kolom ini jika ada di seeder/migration
        'batas_pembayaran_spp', // Tambahkan kolom ini jika ada di seeder/migration
    ];

    // Mendefinisikan casting untuk atribut.
    // Karena Anda menggunakan json_encode() di seeder, casting ke 'array' akan secara otomatis
    // mengubah string JSON menjadi array PHP ketika diambil dari database.
    protected $casts = [
        'catatan_penting' => 'array',
        'tanggal_orientasi' => 'date',   // Casting untuk kolom tanggal
        'batas_pembayaran_spp' => 'date', // Casting untuk kolom tanggal
    ];
}
