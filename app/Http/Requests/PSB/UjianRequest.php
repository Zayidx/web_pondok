<?php

namespace App\Http\Requests\PSB;

use Illuminate\Foundation\Http\FormRequest;

class UjianRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|string',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'durasi' => 'required|integer|min:1',
            'status' => 'required|in:sedang_ujian,selesai',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute harus diisi',
            'array' => ':attribute harus berupa array',
            'string' => ':attribute harus berupa teks',
            'date' => 'Format tanggal tidak valid',
            'after' => ':attribute harus setelah waktu mulai',
            'integer' => ':attribute harus berupa angka',
            'min' => ':attribute minimal :min',
            'in' => ':attribute tidak valid',
        ];
    }

    public function attributes()
    {
        return [
            'jawaban' => 'Jawaban',
            'jawaban.*' => 'Jawaban',
            'waktu_mulai' => 'Waktu mulai',
            'waktu_selesai' => 'Waktu selesai',
            'durasi' => 'Durasi',
            'status' => 'Status',
        ];
    }
} 