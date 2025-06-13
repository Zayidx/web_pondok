<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Http\Requests\PSB\PendaftaranRequest;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    public function store(PendaftaranRequest $request)
    {
        try {
            $data = $request->validated();

            // Upload files
            $data['foto'] = $request->file('foto')->store('public/foto');
            $data['ijazah'] = $request->file('ijazah')->store('public/ijazah');
            $data['kk'] = $request->file('kk')->store('public/kk');
            $data['akta'] = $request->file('akta')->store('public/akta');
            $data['skhun'] = $request->file('skhun')->store('public/skhun');
            $data['kartu_kesehatan'] = $request->file('kartu_kesehatan')->store('public/kartu_kesehatan');

            if ($request->hasFile('sertifikat_hafalan')) {
                $data['sertifikat_hafalan'] = $request->file('sertifikat_hafalan')->store('public/sertifikat_hafalan');
            }

            if ($request->hasFile('sertifikat_prestasi')) {
                $data['sertifikat_prestasi'] = $request->file('sertifikat_prestasi')->store('public/sertifikat_prestasi');
            }

            // Create pendaftaran
            $pendaftaran = PendaftaranSantri::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran berhasil disimpan',
                'data' => $pendaftaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(PendaftaranRequest $request, PendaftaranSantri $pendaftaran)
    {
        try {
            $data = $request->validated();

            // Upload files if new files are provided
            if ($request->hasFile('foto')) {
                Storage::delete($pendaftaran->foto);
                $data['foto'] = $request->file('foto')->store('public/foto');
            }

            if ($request->hasFile('ijazah')) {
                Storage::delete($pendaftaran->ijazah);
                $data['ijazah'] = $request->file('ijazah')->store('public/ijazah');
            }

            if ($request->hasFile('kk')) {
                Storage::delete($pendaftaran->kk);
                $data['kk'] = $request->file('kk')->store('public/kk');
            }

            if ($request->hasFile('akta')) {
                Storage::delete($pendaftaran->akta);
                $data['akta'] = $request->file('akta')->store('public/akta');
            }

            if ($request->hasFile('skhun')) {
                Storage::delete($pendaftaran->skhun);
                $data['skhun'] = $request->file('skhun')->store('public/skhun');
            }

            if ($request->hasFile('kartu_kesehatan')) {
                Storage::delete($pendaftaran->kartu_kesehatan);
                $data['kartu_kesehatan'] = $request->file('kartu_kesehatan')->store('public/kartu_kesehatan');
            }

            if ($request->hasFile('sertifikat_hafalan')) {
                Storage::delete($pendaftaran->sertifikat_hafalan);
                $data['sertifikat_hafalan'] = $request->file('sertifikat_hafalan')->store('public/sertifikat_hafalan');
            }

            if ($request->hasFile('sertifikat_prestasi')) {
                Storage::delete($pendaftaran->sertifikat_prestasi);
                $data['sertifikat_prestasi'] = $request->file('sertifikat_prestasi')->store('public/sertifikat_prestasi');
            }

            // Update pendaftaran
            $pendaftaran->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data pendaftaran berhasil diperbarui',
                'data' => $pendaftaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
} 