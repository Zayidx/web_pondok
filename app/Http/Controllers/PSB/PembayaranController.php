<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Http\Requests\PSB\PembayaranRequest;
use App\Models\PSB\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function store(PembayaranRequest $request)
    {
        try {
            $data = $request->validated();

            // Upload bukti pembayaran
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('public/bukti_pembayaran');

            // Create pembayaran
            $pembayaran = Pembayaran::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil disimpan',
                'data' => $pembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(PembayaranRequest $request, Pembayaran $pembayaran)
    {
        try {
            $data = $request->validated();

            // Upload bukti pembayaran if new file is provided
            if ($request->hasFile('bukti_pembayaran')) {
                Storage::delete($pembayaran->bukti_pembayaran);
                $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('public/bukti_pembayaran');
            }

            // Update pembayaran
            $pembayaran->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data pembayaran berhasil diperbarui',
                'data' => $pembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
} 