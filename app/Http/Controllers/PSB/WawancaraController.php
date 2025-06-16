<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Http\Requests\PSB\WawancaraRequest;
use App\Models\PSB\Wawancara;
use Illuminate\Http\Request;

class WawancaraController extends Controller
{
    public function store(WawancaraRequest $request)
    {
        try {
            $data = $request->validated();

            // Create wawancara
            $wawancara = Wawancara::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal wawancara berhasil disimpan',
                'data' => $wawancara
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(WawancaraRequest $request, Wawancara $wawancara)
    {
        try {
            $data = $request->validated();

            // Update wawancara
            $wawancara->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal wawancara berhasil diperbarui',
                'data' => $wawancara
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
} 