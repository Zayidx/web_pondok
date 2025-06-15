<?php

namespace App\Http\Requests\PSB;

use Illuminate\Foundation\Http\FormRequest;

class PengumumanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tanggal_pengumuman' => 'required|date|after:today',
            'jam_pengumuman' => 'required|date_format:H:i',
            'status' => 'required|in:diterima,ditolak,daftar_ulang',
            'catatan' => 'nullable|string|max:500',
            'file_pengumuman' => 'nullable|mimes:pdf|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute harus diisi',
            'date' => 'Format tanggal tidak valid',
            'after' => ':attribute harus setelah hari ini',
            'date_format' => 'Format jam tidak valid',
            'in' => ':attribute tidak valid',
            'string' => ':attribute harus berupa teks',
            'max' => ':attribute maksimal :max karakter',
            'mimes' => 'File harus berformat :values',
        ];
    }

    public function attributes()
    {
        return [
            'tanggal_pengumuman' => 'Tanggal pengumuman',
            'jam_pengumuman' => 'Jam pengumuman',
            'status' => 'Status',
            'catatan' => 'Catatan',
            'file_pengumuman' => 'File pengumuman',
        ];
    }
} 