<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Http\Requests\PSB\DaftarUlangRequest;
use App\Models\PSB\DaftarUlang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DaftarUlangController extends Controller
{
    public function store(DaftarUlangRequest $request)
    {
        try {
            $data = $request->validated();

            // Upload bukti pembayaran
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('public/bukti_pembayaran');

            // Create daftar ulang
            $daftarUlang = DaftarUlang::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Daftar ulang berhasil disimpan',
                'data' => $daftarUlang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(DaftarUlangRequest $request, DaftarUlang $daftarUlang)
    {
        try {
            $data = $request->validated();

            // Upload bukti pembayaran if new file is provided
            if ($request->hasFile('bukti_pembayaran')) {
                Storage::delete($daftarUlang->bukti_pembayaran);
                $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('public/bukti_pembayaran');
            }

            // Update daftar ulang
            $daftarUlang->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data daftar ulang berhasil diperbarui',
                'data' => $daftarUlang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
} 