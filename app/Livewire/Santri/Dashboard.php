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

class Dashboard extends Component
{
    #[Title('Dashboard Santri')]
    public $detailKegiatanModal, $detailPengumumanModal;

    public $profile, $credentials, $timeline_spp, $pembayaran, $jadwalPelajaran, $jadwalHari,$userId;
    public $setStatusSpp, $isMobile;

    public function mount()
    {
        Carbon::setLocale('id');
        // Dapatkan ID dari santri yang sedang login
$santriId = Auth::guard('santri')->id();

// Cari profil santri berdasarkan ID-nya (primary key)
$this->profile = Santri::with('kamar', 'kelas', 'semester', 'angkatan')->find($santriId);
        $this->timeline_spp = PembayaranTimeline::all();

        $this->setStatusSpp = Carbon::now()->translatedFormat('F');

        $this->jadwalHari = Carbon::now()->translatedFormat('l');

        $mobile = new MobileDetect();
        $this->isMobile = $mobile->isMobile();

        $this->updatedSetStatusSpp($this->setStatusSpp);
    }

    #[Computed]
    public function getMataPelajaran()
    {
        return $this->jadwalPelajaran = $this->profile->kelas->jenjang->jadwalPelajaran()
            ->when($this->jadwalHari)->where('hari', 'LIKE', "%{$this->jadwalHari}%")
            ->get();
    }

    public function updatedSetStatusSpp($value)
    {
         // Pastikan $this->profile tidak null sebelum menjalankan query
    if (!$this->profile) {
        $this->pembayaran = null;
        return;
    }
        $this->pembayaran = Pembayaran::with('pembayaranTimeline', 'santri')
        ->where('santri_id', $this->profile->id) // Menggunakan ID santri
        ->whereHas('pembayaranTimeline', function ($query) use ($value) {
            return $query->where('nama_bulan', $value);
        })->first();
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
