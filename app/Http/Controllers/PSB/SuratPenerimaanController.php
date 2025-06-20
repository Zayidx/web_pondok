<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\SuratPenerimaanSetting;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log; // Opsional, untuk debugging


class SuratPenerimaanController extends Controller
{
 
    private function preparePdfVariables($id)
{
    $data = PendaftaranSantri::findOrFail($id);
    $settings = SuratPenerimaanSetting::first();

    if (!$settings) {
        return [null, null];
    }

    $getImagePath = function ($path) {
        if (!$path) {
            return null;
        }

        $relativePath = $path;
        if (strpos($relativePath, 'public/') === 0) {
            $relativePath = substr($relativePath, 7);
        }

        $fullPath = public_path('storage/' . $relativePath);

      
        if (File::exists($fullPath)) {
            return $fullPath;
        }
        
   
        Log::error("File untuk PDF tidak ditemukan di: " . $fullPath);
        return null;
    };

   
    $settings->logo_path = $getImagePath($settings->logo);
    $settings->ttd_direktur_path = $getImagePath($settings->ttd_direktur);
    $settings->ttd_admin_path = $getImagePath($settings->ttd_admin);

    return [$data, $settings];
}


    public function previewPage($id)
    {
        [$data, $settings] = $this->preparePdfVariables($id);

        if (!$settings || !$data) {
            return redirect()->route('psb.check-status')->with('error', 'Gagal memuat data atau pengaturan surat penerimaan.');
        }

        return view('livewire.admin.psb.surat-penerimaan', compact('data', 'settings'));
    }


    public function preview($id)
    {
        [$data, $settings] = $this->preparePdfVariables($id);

        if (!$settings || !$data) {
            return redirect()->route('psb.check-status')->with('error', 'Gagal memuat data atau pengaturan surat penerimaan.');
        }

        $pdf = PDF::loadView('livewire.admin.psb.surat-penerimaan', compact('data', 'settings'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('surat-penerimaan.pdf');
    }


public function download($id)
{
    [$data, $settings] = $this->preparePdfVariables($id);

    if (!$settings || !$data) {
        return redirect()->route('psb.check-status')->with('error', 'Gagal memuat data atau pengaturan surat penerimaan.');
    }

    $pdf = PDF::loadView('livewire.admin.psb.surat-penerimaan', compact('data', 'settings'));
    $pdf->setPaper('a4', 'portrait');
    return $pdf->download('surat-penerimaan-' . $data->nama_lengkap . '.pdf');
}


    public function print($id)
    {
      
        return $this->preview($id);
    }
}