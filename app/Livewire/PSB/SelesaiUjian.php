<?php

namespace App\Livewire\PSB;

use Livewire\Component;
use App\Models\PSB\Ujian;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class SelesaiUjian extends Component
{
    #[Layout('components.layouts.ujian')]
    #[Title('Selesai Ujian')]

    public $ujian;
    public $santri;
    public $hasilUjian;
    public $waktuMulai;
    public $waktuSelesai;
    public $durasiPengerjaan;
    public $jumlahSoal;
    public $soalTerjawab;

    public function mount($ujianId)
    {
        // Get santri data from auth
        $this->santri = Auth::guard('santri')->user();
        if (!$this->santri) {
            $this->santri = PendaftaranSantri::find(session('santri_id'));
        }

        if (!$this->santri) {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        // Get exam data with soal count
        $this->ujian = Ujian::withCount('soals')->findOrFail($ujianId);
        $this->jumlahSoal = $this->ujian->soals_count;

        // Get hasil ujian
        $this->hasilUjian = HasilUjian::where('santri_id', $this->santri->id)
            ->where('ujian_id', $this->ujian->id)
            ->firstOrFail();

        // Set waktu mulai dan selesai
        $this->waktuMulai = Carbon::parse($this->hasilUjian->waktu_mulai);
        $this->waktuSelesai = Carbon::parse($this->hasilUjian->waktu_selesai);
        $this->durasiPengerjaan = $this->waktuMulai->diffInMinutes($this->waktuSelesai);

        // Get jumlah soal terjawab
        $this->soalTerjawab = $this->hasilUjian->jawabanUjians()->count();

        // Update santri status if needed
        if ($this->santri->status_santri === 'menunggu') {
            $this->santri->update(['status_santri' => 'sedang_ujian']);
        }
    }

    public function render()
    {
        return view('livewire.psb.selesai-ujian');
    }
} 