<?php

namespace App\Http\Requests\PSB;

use Illuminate\Foundation\Http\FormRequest;

class WawancaraRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tanggal_wawancara' => 'required|date|after:today',
            'jam_wawancara' => 'required|date_format:H:i',
            'mode_wawancara' => 'required|in:online,offline',
            'link_meeting' => 'required_if:mode_wawancara,online|url|nullable',
            'catatan' => 'nullable|string|max:500',
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
            'required_if' => ':attribute harus diisi jika mode wawancara online',
            'url' => 'Format URL tidak valid',
            'string' => ':attribute harus berupa teks',
            'max' => ':attribute maksimal :max karakter',
        ];
    }

    public function attributes()
    {
        return [
            'tanggal_wawancara' => 'Tanggal wawancara',
            'jam_wawancara' => 'Jam wawancara',
            'mode_wawancara' => 'Mode wawancara',
            'link_meeting' => 'Link meeting',
            'catatan' => 'Catatan',
        ];
    }
} 