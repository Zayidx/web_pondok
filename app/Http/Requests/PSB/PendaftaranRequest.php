<?php

namespace App\Http\Requests\PSB;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PendaftaranRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Data Pribadi
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'kewarganegaraan' => 'required|string|max:50',
            'anak_ke' => 'required|integer|min:1',
            'jumlah_saudara' => 'required|integer|min:0',
            'status_anak' => 'required|in:Yatim,Piatu,Yatim Piatu,Lengkap',
            'bahasa_sehari_hari' => 'required|string|max:100',
            'golongan_darah' => 'required|in:A,B,AB,O',
            'riwayat_penyakit' => 'nullable|string|max:500',
            'alamat_lengkap' => 'required|string|max:500',
            'kode_pos' => 'required|string|max:10',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|unique:pendaftaran_santris,email',
            'tinggal_bersama' => 'required|in:Orang Tua,Wali',
            'jarak_ke_pondok' => 'required|numeric|min:0',
            'transportasi' => 'required|string|max:100',
            'hobi' => 'required|string|max:200',
            'cita_cita' => 'required|string|max:200',

            // Data Orang Tua
            'nama_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:100',
            'pendidikan_ayah' => 'required|string|max:100',
            'penghasilan_ayah' => 'required|numeric|min:0',
            'no_hp_ayah' => 'required|string|max:20',
            'alamat_ayah' => 'required|string|max:500',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:100',
            'pendidikan_ibu' => 'required|string|max:100',
            'penghasilan_ibu' => 'required|numeric|min:0',
            'no_hp_ibu' => 'required|string|max:20',
            'alamat_ibu' => 'required|string|max:500',

            // Data Wali (jika tinggal dengan wali)
            'nama_wali' => 'required_if:tinggal_bersama,Wali|string|max:255',
            'pekerjaan_wali' => 'required_if:tinggal_bersama,Wali|string|max:100',
            'pendidikan_wali' => 'required_if:tinggal_bersama,Wali|string|max:100',
            'penghasilan_wali' => 'required_if:tinggal_bersama,Wali|numeric|min:0',
            'no_hp_wali' => 'required_if:tinggal_bersama,Wali|string|max:20',
            'alamat_wali' => 'required_if:tinggal_bersama,Wali|string|max:500',
            'hubungan_wali' => 'required_if:tinggal_bersama,Wali|string|max:100',

            // Data Sekolah
            'asal_sekolah' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:pendaftaran_santris,nisn',
            'tahun_lulus' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'no_ijazah' => 'required|string|max:100',
            'nilai_ijazah' => 'required|numeric|min:0|max:100',

            // File Upload
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'required|mimes:pdf|max:5120',
            'kk' => 'required|mimes:pdf|max:5120',
            'akta' => 'required|mimes:pdf|max:5120',
            'skhun' => 'required|mimes:pdf|max:5120',
            'kartu_kesehatan' => 'required|mimes:pdf|max:5120',
            'sertifikat_hafalan' => 'nullable|mimes:pdf|max:5120',
            'sertifikat_prestasi' => 'nullable|mimes:pdf|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute harus diisi',
            'string' => ':attribute harus berupa teks',
            'max' => ':attribute maksimal :max karakter',
            'min' => ':attribute minimal :min karakter',
            'numeric' => ':attribute harus berupa angka',
            'email' => 'Format email tidak valid',
            'unique' => ':attribute sudah terdaftar',
            'date' => 'Format tanggal tidak valid',
            'in' => ':attribute tidak valid',
            'image' => 'File harus berupa gambar',
            'mimes' => 'File harus berformat :values',
            'required_if' => ':attribute harus diisi jika :other adalah :value',
        ];
    }

    public function attributes()
    {
        return [
            'nama_lengkap' => 'Nama lengkap',
            'jenis_kelamin' => 'Jenis kelamin',
            'tempat_lahir' => 'Tempat lahir',
            'tanggal_lahir' => 'Tanggal lahir',
            'agama' => 'Agama',
            'kewarganegaraan' => 'Kewarganegaraan',
            'anak_ke' => 'Anak ke',
            'jumlah_saudara' => 'Jumlah saudara',
            'status_anak' => 'Status anak',
            'bahasa_sehari_hari' => 'Bahasa sehari-hari',
            'golongan_darah' => 'Golongan darah',
            'riwayat_penyakit' => 'Riwayat penyakit',
            'alamat_lengkap' => 'Alamat lengkap',
            'kode_pos' => 'Kode pos',
            'no_hp' => 'Nomor HP',
            'email' => 'Email',
            'tinggal_bersama' => 'Tinggal bersama',
            'jarak_ke_pondok' => 'Jarak ke pondok',
            'transportasi' => 'Transportasi',
            'hobi' => 'Hobi',
            'cita_cita' => 'Cita-cita',
            'nama_ayah' => 'Nama ayah',
            'pekerjaan_ayah' => 'Pekerjaan ayah',
            'pendidikan_ayah' => 'Pendidikan ayah',
            'penghasilan_ayah' => 'Penghasilan ayah',
            'no_hp_ayah' => 'Nomor HP ayah',
            'alamat_ayah' => 'Alamat ayah',
            'nama_ibu' => 'Nama ibu',
            'pekerjaan_ibu' => 'Pekerjaan ibu',
            'pendidikan_ibu' => 'Pendidikan ibu',
            'penghasilan_ibu' => 'Penghasilan ibu',
            'no_hp_ibu' => 'Nomor HP ibu',
            'alamat_ibu' => 'Alamat ibu',
            'nama_wali' => 'Nama wali',
            'pekerjaan_wali' => 'Pekerjaan wali',
            'pendidikan_wali' => 'Pendidikan wali',
            'penghasilan_wali' => 'Penghasilan wali',
            'no_hp_wali' => 'Nomor HP wali',
            'alamat_wali' => 'Alamat wali',
            'hubungan_wali' => 'Hubungan wali',
            'asal_sekolah' => 'Asal sekolah',
            'nisn' => 'NISN',
            'tahun_lulus' => 'Tahun lulus',
            'no_ijazah' => 'Nomor ijazah',
            'nilai_ijazah' => 'Nilai ijazah',
            'foto' => 'Foto',
            'ijazah' => 'Ijazah',
            'kk' => 'Kartu Keluarga',
            'akta' => 'Akta kelahiran',
            'skhun' => 'SKHUN',
            'kartu_kesehatan' => 'Kartu kesehatan',
            'sertifikat_hafalan' => 'Sertifikat hafalan',
            'sertifikat_prestasi' => 'Sertifikat prestasi',
        ];
    }
} 