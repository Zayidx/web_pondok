<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Http\Requests\PSB\PengumumanRequest;
use App\Models\PSB\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    public function store(PengumumanRequest $request)
    {
        try {
            $data = $request->validated();

            // Upload file pengumuman if provided
            if ($request->hasFile('file_pengumuman')) {
                $data['file_pengumuman'] = $request->file('file_pengumuman')->store('public/pengumuman');
            }

            // Create pengumuman
            $pengumuman = Pengumuman::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengumuman berhasil disimpan',
                'data' => $pengumuman
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(PengumumanRequest $request, Pengumuman $pengumuman)
    {
        try {
            $data = $request->validated();

            // Upload file pengumuman if new file is provided
            if ($request->hasFile('file_pengumuman')) {
                Storage::delete($pengumuman->file_pengumuman);
                $data['file_pengumuman'] = $request->file('file_pengumuman')->store('public/pengumuman');
            }

            // Update pengumuman
            $pengumuman->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengumuman berhasil diperbarui',
                'data' => $pengumuman
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
} 