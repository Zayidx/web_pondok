<?php

namespace App\Livewire\Admin\AdminPiket;

use Livewire\Component;
use App\Models\ESantri\JadwalPelajaran;
use App\Models\Absensi\QrToken;
use App\Models\Absensi\Kehadiran;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Livewire\Attributes\On;

class MulaiSesiAbsensi extends Component
{
    public JadwalPelajaran $jadwal;
    public $qrToken;
    public $listeners = ['kehadiranDicatat' => '$refresh'];

    public function mount($jadwalPelajaranId)
    {
        $this->jadwal = JadwalPelajaran::findOrFail($jadwalPelajaranId);
        $this->generateQrToken();
    }

    public function generateQrToken()
    {
        $token = Str::random(40);
        $expiresAt = now()->addSeconds(60);

        QrToken::create([
            'token' => $token,
            'jadwal_pelajaran_id' => $this->jadwal->id,
            'expires_at' => $expiresAt,
        ]);

        $this->qrToken = $token;
    }
    
    #[On('kehadiranDicatat')]
    public function reRenderList()
    {
        
    }

    public function getKehadiranHariIniProperty()
    {
        return Kehadiran::where('jadwal_pelajaran_id', $this->jadwal->id)
            ->whereDate('tanggal', Carbon::today())
            ->with('santri')
            ->latest('waktu_hadir')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.admin-piket.mulai-sesi-absensi', [
            'daftarHadir' => $this->kehadiranHariIni
        ]);
    }
}