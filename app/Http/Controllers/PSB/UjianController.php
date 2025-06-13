<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Http\Requests\PSB\UjianRequest;
use App\Models\PSB\Ujian;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function store(UjianRequest $request)
    {
        try {
            $data = $request->validated();

            // Create ujian
            $ujian = Ujian::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data ujian berhasil disimpan',
                'data' => $ujian
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(UjianRequest $request, Ujian $ujian)
    {
        try {
            $data = $request->validated();

            // Update ujian
            $ujian->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data ujian berhasil diperbarui',
                'data' => $ujian
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
} 