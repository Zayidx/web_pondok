<?php

namespace App\Livewire\Santri;

use App\Models\Kegiatan;
use App\Models\Pengumuman;
use App\Models\Santri;
use App\Models\Spp\Pembayaran;
use App\Models\Spp\PembayaranTimeline;
use Carbon\Carbon;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Jenssegers\Agent\Agent;

class Dashboard extends Component
{
    #[Title('Dashboard Santri')]
    public $detailKegiatanModal, $detailPengumumanModal;

    public $profile, $credentials, $timeline_spp, $pembayaran, $jadwalPelajaran, $jadwalHari;
    public $setStatusSpp, $isMobile;

    public function mount()
    {
        try {
            Carbon::setLocale('id');

            // Get authenticated user
            $user = Auth::guard('santri')->user();
            if (!$user) {
                throw new \Exception('User tidak ditemukan');
            }

            // Get santri profile
            $this->profile = Santri::with(['kamar', 'kelas', 'semester', 'angkatan'])
                ->where('nama', $user->nama)
                ->first();

            if (!$this->profile) {
                throw new \Exception('Data santri tidak ditemukan');
            }

            // Get timeline SPP
            $this->timeline_spp = PembayaranTimeline::all();

            // Set current month
            $this->setStatusSpp = Carbon::now()->translatedFormat('F');

            // Set current day
            $this->jadwalHari = Carbon::now()->translatedFormat('l');

            // Check if mobile
            $agent = new Agent();
            $this->isMobile = $agent->isMobile();

            // Update SPP status
            $this->updatedSetStatusSpp($this->setStatusSpp);

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->route('login-ppdb-santri');
        }
    }

    #[Computed]
    public function getMataPelajaran()
    {
        try {
            if (!$this->profile || !$this->profile->kelas) {
                return collect();
            }

            return $this->profile->kelas->jadwalPelajaran()
                ->where('hari', $this->jadwalHari)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function updatedSetStatusSpp($value)
    {
        try {
            if (!$this->profile) {
                return;
            }

            $this->pembayaran = $this->profile->pembayaran()
                ->whereMonth('tanggal', Carbon::parse($value)->month)
                ->whereYear('tanggal', Carbon::parse($value)->year)
                ->get();
        } catch (\Exception $e) {
            $this->pembayaran = collect();
        }
    }

    #[Computed]
    public function listPengumuman()
    {
        return Pengumuman::latest()->take(4)->get();
    }

    #[Computed]
    public function listKegiatan()
    {
        return Kegiatan::latest()->take(4)->get();
    }

    public function detailKegiatan($id)
    {
        $this->detailKegiatanModal = Kegiatan::findOrFail($id);
    }

    public function detailPengumuman($id)
    {
        $this->detailPengumumanModal = Pengumuman::findOrFail($id);
    }

    public function render()
    {
        if ($this->isMobile) return view('livewire.mobile.santri.dashboard')->layout('components.layouts.app-mobile');
        return view('livewire.santri.dashboard');
    }
}

