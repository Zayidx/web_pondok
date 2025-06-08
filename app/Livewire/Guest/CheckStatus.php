<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Log;
use Livewire\WithoutUrlPagination;

class CheckStatus extends Component
{
    use WithoutUrlPagination;
    
    public $santri = null;
    public $timelineStatus = [
        'pendaftaran_online' => [
            'completed' => false,
            'date' => null,
        ],
        'wawancara' => [
            'completed' => false,
            'current' => false,
            'date' => null,
            'time' => null,
            'mode' => null,
            'location' => null,
        ],
        'ujian' => [
            'completed' => false,
            'current' => false,
            'date' => null,
        ],
        'pengumuman_hasil' => [
            'completed' => false,
            'current' => false,
            'status' => null,
            'date' => null,
        ],
    ];

    public function mount()
    {
        $santriId = session('santri_id');
        if (!$santriId) {
            Log::warning('No santri_id in session, redirecting to login');
            return redirect()->route('login-ppdb-santri');
        }

        $this->santri = PendaftaranSantri::where('id', $santriId)->first();
        if (!$this->santri) {
            Log::warning('Santri not found for ID: ' . $santriId);
            session()->forget('santri_id');
            return redirect()->route('login-ppdb-santri');
        }

        // // If santri is in exam phase, redirect directly to exam dashboard
        // if ($this->santri->status_santri === 'sedang_ujian') {
        //     return redirect()->route('santri.dashboard-ujian');
        // }

        // Set timeline status
        $this->updateTimelineStatus();

        Log::info('Santri data retrieved for logged-in user', [
            'santri_id' => $this->santri->id,
            'nama_lengkap' => $this->santri->nama_lengkap,
            'nisn' => $this->santri->nisn,
            'status_santri' => $this->santri->status_santri,
            'timeline_status' => $this->timelineStatus,
        ]);
    }

    protected function updateTimelineStatus()
    {
        if (!$this->santri) {
            return;
        }

        $this->timelineStatus['pendaftaran_online'] = [
            'completed' => true,
            'date' => $this->santri->created_at ? $this->santri->created_at->format('d F Y') : 'N/A',
        ];

        $this->timelineStatus['wawancara'] = [
            'completed' => in_array($this->santri->status_santri, ['sedang_ujian', 'diterima', 'ditolak']),
            'current' => $this->santri->status_santri == 'wawancara',
            'date' => $this->santri->tanggal_wawancara ? \Carbon\Carbon::parse($this->santri->tanggal_wawancara)->format('d F Y') : null,
            'time' => $this->santri->tanggal_wawancara ? \Carbon\Carbon::parse($this->santri->tanggal_wawancara)->format('H:i') : null,
            'mode' => $this->santri->mode ?? 'offline',
            'location' => $this->santri->mode == 'offline' ? ($this->santri->lokasi_offline ?? 'Ruang Wawancara') : ($this->santri->link_online ?? '#'),
        ];

        $this->timelineStatus['ujian'] = [
            'completed' => in_array($this->santri->status_santri, ['diterima', 'ditolak']),
            'current' => $this->santri->status_santri == 'sedang_ujian',
            'date' => $this->santri->updated_at && in_array($this->santri->status_santri, ['diterima', 'ditolak']) 
                ? $this->santri->updated_at->format('d F Y') 
                : null,
        ];

        $this->timelineStatus['pengumuman_hasil'] = [
            'completed' => in_array($this->santri->status_santri, ['diterima', 'ditolak']),
            'current' => false,
            'status' => in_array($this->santri->status_santri, ['diterima', 'ditolak']) ? $this->santri->status_santri : null,
            'date' => $this->santri->updated_at && in_array($this->santri->status_santri, ['diterima', 'ditolak']) 
                ? $this->santri->updated_at->format('d F Y') 
                : null,
        ];
    }

    public function logout()
    {
        Log::info('Santri logged out', ['santri_id' => session('santri_id')]);
        session()->forget('santri_id');
        return redirect()->route('login-ppdb-santri');
    }

    public function render()
    {
        return view('livewire.guest.check-status', [
            'title' => 'Cek Status Pendaftaran'
        ])->extends('components.layouts.check-status');
    }
}