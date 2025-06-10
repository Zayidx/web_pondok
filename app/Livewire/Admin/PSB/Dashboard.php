<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\Periode;
use App\Models\PSB\HasilUjian;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    #[Title('Dashboard Pendaftaran Santri Baru')]
    public function render()
    {
        // Get active period
        $periode = Periode::where('status_periode', 'active')->first();
        
        // Get registration statistics
        $totalPendaftar = PendaftaranSantri::when($periode, function($query) use ($periode) {
            return $query->where('periode_id', $periode->id);
        })->count();

        $pendaftarByGender = PendaftaranSantri::when($periode, function($query) use ($periode) {
            return $query->where('periode_id', $periode->id);
        })
        ->select('jenis_kelamin', DB::raw('count(*) as total'))
        ->groupBy('jenis_kelamin')
        ->get();

        $pendaftarByProgram = PendaftaranSantri::when($periode, function($query) use ($periode) {
            return $query->where('periode_id', $periode->id);
        })
        ->select('tipe_pendaftaran', DB::raw('count(*) as total'))
        ->groupBy('tipe_pendaftaran')
        ->get();

        $pendaftarByStatus = PendaftaranSantri::when($periode, function($query) use ($periode) {
            return $query->where('periode_id', $periode->id);
        })
        ->select('status_santri', DB::raw('count(*) as total'))
        ->groupBy('status_santri')
        ->get();

        // Get total santri yang sedang wawancara
        $totalWawancara = PendaftaranSantri::when($periode, function($query) use ($periode) {
            return $query->where('periode_id', $periode->id);
        })
        ->where('status_santri', 'wawancara')
        ->count();

        // Get total santri yang sedang ujian
        $totalUjian = PendaftaranSantri::when($periode, function($query) use ($periode) {
            return $query->where('periode_id', $periode->id);
        })
        ->where('status_santri', 'sedang_ujian')
        ->count();

        // Get total santri yang diterima
        $totalDiterima = PendaftaranSantri::when($periode, function($query) use ($periode) {
            return $query->where('periode_id', $periode->id);
        })
        ->where('status_santri', 'diterima')
        ->count();

        // Get total santri yang ditolak
        $totalDitolak = PendaftaranSantri::when($periode, function($query) use ($periode) {
            return $query->where('periode_id', $periode->id);
        })
        ->where('status_santri', 'ditolak')
        ->count();

        // Get recent registrations
        $recentRegistrations = PendaftaranSantri::when($periode, function($query) use ($periode) {
            return $query->where('periode_id', $periode->id);
        })
        ->with(['periode', 'hasilUjians'])
        ->latest()
        ->take(5)
        ->get();

        // Get exam statistics
        $examStats = HasilUjian::when($periode, function($query) use ($periode) {
            return $query->whereHas('santri', function($q) use ($periode) {
                $q->where('periode_id', $periode->id);
            });
        })
        ->select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->get();

        return view('livewire.admin.psb.dashboard', [
            'periode' => $periode,
            'totalPendaftar' => $totalPendaftar,
            'pendaftarByGender' => $pendaftarByGender,
            'pendaftarByProgram' => $pendaftarByProgram,
            'pendaftarByStatus' => $pendaftarByStatus,
            'recentRegistrations' => $recentRegistrations,
            'totalWawancara' => $totalWawancara,
            'totalUjian' => $totalUjian,
            'totalDiterima' => $totalDiterima,
            'totalDitolak' => $totalDitolak,
            'examStats' => $examStats,
        ]);
    }
}

