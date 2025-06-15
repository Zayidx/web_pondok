<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\SuratPenerimaanSetting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SuratPenerimaanController extends Controller
{
    private function getBase64Image($path)
    {
        if (!$path) return null;
        
        try {
            $imagePath = Storage::path(str_replace('public/', '', $path));
            if (file_exists($imagePath)) {
                $imageData = file_get_contents($imagePath);
                $base64 = base64_encode($imageData);
                $mime = mime_content_type($imagePath);
                return "data:{$mime};base64,{$base64}";
            }
        } catch (\Exception $e) {
            return null;
        }
        
        return null;
    }

    /**
     * Menampilkan halaman preview sertifikat
     */
    public function previewPage($id)
    {
        $data = PendaftaranSantri::findOrFail($id);
        $settings = SuratPenerimaanSetting::first();

        if (!$settings) {
            return redirect()->route('psb.check-status')->with('error', 'Pengaturan surat penerimaan belum dikonfigurasi.');
        }

        // Convert images to base64
        $settings->logo_base64 = $this->getBase64Image($settings->logo);
        $settings->stempel_base64 = $this->getBase64Image($settings->stempel);

        return view('psb.preview-sertifikat', compact('data', 'settings'));
    }

    /**
     * Menampilkan preview surat penerimaan
     */
    public function preview($id)
    {
        $data = PendaftaranSantri::findOrFail($id);
        $settings = SuratPenerimaanSetting::first();

        if (!$settings) {
            return redirect()->route('psb.check-status')->with('error', 'Pengaturan surat penerimaan belum dikonfigurasi.');
        }

        // Convert images to base64
        $settings->logo_base64 = $this->getBase64Image($settings->logo);
        $settings->stempel_base64 = $this->getBase64Image($settings->stempel);

        $pdf = PDF::loadView('psb.surat-penerimaan', compact('data', 'settings'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('surat-penerimaan.pdf');
    }

    /**
     * Mengunduh surat penerimaan dalam format PDF
     */
    public function download($id)
    {
        $data = PendaftaranSantri::findOrFail($id);
        $settings = SuratPenerimaanSetting::first();

        if (!$settings) {
            return redirect()->route('psb.check-status')->with('error', 'Pengaturan surat penerimaan belum dikonfigurasi.');
        }

        // Convert images to base64
        $settings->logo_base64 = $this->getBase64Image($settings->logo);
        $settings->stempel_base64 = $this->getBase64Image($settings->stempel);

        $pdf = PDF::loadView('psb.surat-penerimaan', compact('data', 'settings'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('surat-penerimaan-' . $data->nama_lengkap . '.pdf');
    }

    /**
     * Mencetak surat penerimaan
     */
    public function print($id)
    {
        $data = PendaftaranSantri::findOrFail($id);
        $settings = SuratPenerimaanSetting::first();

        if (!$settings) {
            return redirect()->route('psb.check-status')->with('error', 'Pengaturan surat penerimaan belum dikonfigurasi.');
        }

        // Convert images to base64
        $settings->logo_base64 = $this->getBase64Image($settings->logo);
        $settings->stempel_base64 = $this->getBase64Image($settings->stempel);

        $pdf = PDF::loadView('psb.surat-penerimaan', compact('data', 'settings'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('surat-penerimaan.pdf');
    }
} 