<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Log;

class CheckStatus extends Component
{
    public $santri = null;
    public $timelineStatus = [];

    public function mount()
    {
        $santriId = session('santri_id');
        if (!$santriId) {
            Log::warning('No santri_id in session, redirecting to login');
            return redirect()->route('login-psb');
        }

        $this->santri = PendaftaranSantri::where('id', $santriId)->first();
        if (!$this->santri) {
            Log::warning('Santri not found for ID: ' . $santriId);
            session()->forget('santri_id');
            return redirect()->route('login-psb');
        }

        // Determine timeline status based on santri data
        $this->timelineStatus = [
            'pendaftaran_online' => [
                'completed' => true, // Always completed since santri exists
                'date' => $this->santri->created_at ? $this->santri->created_at->format('d F Y') : 'N/A',
            ],
            'verifikasi_berkas' => [
                'completed' => true, // Assuming berkas is always "lengkap" as per static request
                'date' => $this->santri->created_at ? $this->santri->created_at->addDays(3)->format('d F Y') : 'N/A', // Assume 3 days after registration
            ],
            'tes_potensi_akademik' => [
                'completed' => true, // Assume completed for timeline (static date in Blade)
                'date' => '05 April 2025', // Static as per Blade
            ],
            'wawancara' => [
                'completed' => $this->santri->status_santri == 'diterima' || $this->santri->status_santri == 'ditolak',
                'current' => $this->santri->status_santri == 'menunggu',
                'date' => $this->santri->tanggal_wawancara ? \Carbon\Carbon::parse($this->santri->tanggal_wawancara)->format('d F Y') : null,
            ],
            'pengumuman_hasil' => [
                'completed' => $this->santri->status_santri == 'diterima' || $this->santri->status_santri == 'ditolak',
                'date' => '20 April 2025', // Static as per Blade
            ],
        ];

        Log::info('Santri data retrieved for logged-in user', [
            'santri_id' => $this->santri->id,
            'nama_lengkap' => $this->santri->nama_lengkap,
            'nisn' => $this->santri->nisn,
            'status_santri' => $this->santri->status_santri,
            'timeline_status' => $this->timelineStatus,
        ]);
    }

    public function logout()
    {
        Log::info('Santri logged out', ['santri_id' => session('santri_id')]);
        session()->forget('santri_id');
        return redirect()->route('login-psb');
    }

    public function render()
    {
        return view('livewire.guest.check-status')->layout('components.layouts.check-status', ['title' => 'Cek Status Pendaftaran']);
    }
}