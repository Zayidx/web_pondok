<?php

namespace App\Livewire\PSB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;

class CheckStatus extends Component
{
    public $nisn;
    public $santri;
    public $message;
    public $timelineStatus;

    public function mount()
    {
        // If santri_id exists in session, load the santri data
        if (Session::has('santri_id')) {
            $this->santri = PendaftaranSantri::find(Session::get('santri_id'));
            $this->setTimelineStatus();
        }
    }

    public function checkStatus()
    {
        $this->validate([
            'nisn' => 'required|numeric|digits:10'
        ]);

        $this->santri = PendaftaranSantri::where('nisn', $this->nisn)->first();

        if (!$this->santri) {
            $this->message = 'NISN tidak ditemukan dalam database.';
            return;
        }

        $this->setTimelineStatus();
    }

    private function setTimelineStatus()
    {
        $this->timelineStatus = [
            'pendaftaran_online' => [
                'completed' => true,
                'date' => $this->santri->created_at->format('d F Y')
            ],
            'wawancara' => [
                'completed' => in_array($this->santri->status_santri, ['sedang_ujian', 'lulus', 'diterima']),
                'current' => $this->santri->status_santri === 'menunggu_wawancara',
                'date' => $this->santri->tanggal_wawancara ? $this->santri->tanggal_wawancara->format('d F Y') : null,
                'time' => $this->santri->tanggal_wawancara ? $this->santri->tanggal_wawancara->format('H:i') : null,
                'mode' => $this->santri->mode_wawancara ?? 'offline',
                'location' => $this->santri->mode_wawancara === 'online' ? 
                    $this->santri->link_wawancara : 
                    $this->santri->lokasi_wawancara ?? 'Ruang Wawancara'
            ],
            'ujian' => [
                'completed' => in_array($this->santri->status_santri, ['lulus', 'diterima']),
                'current' => $this->santri->status_santri === 'sedang_ujian',
                'date' => $this->santri->tanggal_ujian ? $this->santri->tanggal_ujian->format('d F Y') : null
            ]
        ];
    }

    public function goToDashboard()
    {
        if (!Session::has('santri_id')) {
            return redirect()->route('login-ppdb-santri');
        }
        return redirect()->route('santri.dashboard-ujian');
    }

    #[Layout('components.layouts.check-status')]
    public function render()
    {
        return view('livewire.guest.check-status');
    }
} 