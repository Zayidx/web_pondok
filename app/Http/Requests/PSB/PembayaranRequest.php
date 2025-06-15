<?php

namespace App\Http\Requests\PSB;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nominal_pembayaran' => 'required|numeric|min:0',
            'tanggal_pembayaran' => 'required|date',
            'bank_pengirim' => 'required|string|max:100',
            'nama_pengirim' => 'required|string|max:255',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute minimal :min',
            'date' => 'Format tanggal tidak valid',
            'string' => ':attribute harus berupa teks',
            'max' => ':attribute maksimal :max karakter',
            'image' => 'File harus berupa gambar',
            'mimes' => 'File harus berformat :values',
        ];
    }

    public function attributes()
    {
        return [
            'nominal_pembayaran' => 'Nominal pembayaran',
            'tanggal_pembayaran' => 'Tanggal pembayaran',
            'bank_pengirim' => 'Bank pengirim',
            'nama_pengirim' => 'Nama pengirim',
            'bukti_pembayaran' => 'Bukti pembayaran',
        ];
    }
} 