<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use App\Models\HasilUjian;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

class DashboardUjianSantri extends Component
{
    #[Layout('components.layouts.ujian')]
    #[Title('Dashboard Ujian')]

    public $santri;
    /** @var \Illuminate\Database\Eloquent\Collection */
    public $ujianList;
    /** @var array */
    public $ujianSelesai = [];
    /** @var array */
    public $ujianTersedia = [];
    /** @var array */
    public $ujianBelumTersedia = [];
    public int $total_ujian = 0;
    public int $selesai = 0;
    public int $tersedia = 0;
    public int $belum_tersedia = 0;

    public function mount()
    {
        // Get santri data from auth
        $this->santri = Auth::guard('santri')->user();
        if (!$this->santri) {
            $this->santri = PendaftaranSantri::find(session('santri_id'));
        }
        
        if (!$this->santri || $this->santri->status_santri !== 'sedang_ujian') {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        // Get all exams with soal count
        $this->ujianList = Ujian::withCount('soals')->get();
        $this->total_ujian = $this->ujianList->count();
        
        // Get completed exams with details
        $hasilUjianSelesai = HasilUjian::where('santri_id', $this->santri->id)
            ->where('status', 'selesai')
            ->with(['ujian' => function($query) {
                $query->withCount('soals');
            }])
            ->get();

        // Count completed exams
        $this->selesai = $hasilUjianSelesai->count();

        // Format completed exams for view
        $this->ujianSelesai = $hasilUjianSelesai->map(function ($hasil) {
            $waktuMulai = Carbon::parse($hasil->ujian->waktu_mulai);
            $waktuSelesai = Carbon::parse($hasil->ujian->waktu_selesai);
            $durasi = $waktuMulai->diffInMinutes($waktuSelesai);
            
            return [
                'id' => $hasil->ujian->id,
                'nama' => $hasil->ujian->mata_pelajaran,
                'tanggal' => $hasil->waktu_selesai->format('d M Y'),
                'waktu_mulai' => $hasil->waktu_mulai->format('H:i'),
                'waktu_selesai' => $hasil->waktu_selesai->format('H:i'),
                'nilai' => $hasil->nilai,
                'total_skor' => $hasil->total_skor,
                'durasi' => $durasi . ' menit',
                'jumlah_soal' => $hasil->ujian->soals_count . ' soal'
            ];
        })->toArray();

        // Get available and unavailable exams
        $selesaiIds = $hasilUjianSelesai->pluck('ujian_id')->toArray();
        
        // Get all active exams that haven't been completed, sorted by time
        $ujianTersedia = $this->ujianList
            ->where('status_ujian', 'aktif')
            ->whereNotIn('id', $selesaiIds)
            ->sortBy('waktu_mulai');
        
        // Format available exams for view
        $this->ujianTersedia = $ujianTersedia->map(function ($ujian) {
            $waktuMulai = Carbon::parse($ujian->waktu_mulai);
            $waktuSelesai = Carbon::parse($ujian->waktu_selesai);
            $durasi = $waktuMulai->diffInMinutes($waktuSelesai);
            
            return [
                'id' => $ujian->id,
                'nama' => $ujian->mata_pelajaran,
                'tanggal' => Carbon::parse($ujian->tanggal_ujian)->format('d M Y'),
                'waktu_mulai' => $ujian->waktu_mulai,
                'waktu_selesai' => $ujian->waktu_selesai,
                'waktu' => $ujian->waktu_mulai . ' - ' . $ujian->waktu_selesai,
                'durasi' => $durasi . ' menit',
                'jumlah_soal' => $ujian->soals_count . ' soal'
            ];
        })->toArray();

        // Get inactive exams that haven't been completed
        $ujianBelumTersedia = $this->ujianList
            ->whereNotIn('id', $selesaiIds)
            ->where('status_ujian', '!=', 'aktif');

        // Format unavailable exams for view
        $this->ujianBelumTersedia = $ujianBelumTersedia->map(function ($ujian) {
            $waktuMulai = Carbon::parse($ujian->waktu_mulai);
            $waktuSelesai = Carbon::parse($ujian->waktu_selesai);
            $durasi = $waktuMulai->diffInMinutes($waktuSelesai);
            
            return [
                'id' => $ujian->id,
                'nama' => $ujian->mata_pelajaran,
                'tanggal' => Carbon::parse($ujian->tanggal_ujian)->format('d M Y'),
                'waktu_mulai' => $ujian->waktu_mulai,
                'waktu_selesai' => $ujian->waktu_selesai,
                'waktu' => $ujian->waktu_mulai . ' - ' . $ujian->waktu_selesai,
                'durasi' => $durasi . ' menit',
                'jumlah_soal' => $ujian->soals_count . ' soal',
                'status' => 'Belum Aktif'
            ];
        })->toArray();

        // Update counters
        $this->tersedia = count($this->ujianTersedia);
        $this->belum_tersedia = count($this->ujianBelumTersedia);
    }

    public function lihatHasil($ujianId)
    {
        return redirect()->route('santri.selesai-ujian', ['ujianId' => $ujianId]);
    }

    public function mulaiUjian($ujianId)
    {
        return redirect()->route('santri.konfirmasi-ujian', ['ujianId' => $ujianId]);
    }

    public function render()
    {
        return view('livewire.santri-p-p-d-b.dashboard-ujian-santri');
    }
} 