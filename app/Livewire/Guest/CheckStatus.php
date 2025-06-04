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
    public $timelineStatus = [];

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

        // Determine timeline status based on santri data
        $this->timelineStatus = [
            'pendaftaran_online' => [
                'completed' => true, // Always completed since santri exists
                'date' => $this->santri->created_at ? $this->santri->created_at->format('d F Y') : 'N/A',
            ],
            'wawancara' => [
                'completed' => in_array($this->santri->status_santri, ['diterima', 'ditolak']),
                'current' => $this->santri->status_santri == 'wawancara',
                'date' => $this->santri->tanggal_wawancara ? \Carbon\Carbon::parse($this->santri->tanggal_wawancara)->format('d F Y') : null,
                'time' => $this->santri->tanggal_wawancara ? \Carbon\Carbon::parse($this->santri->tanggal_wawancara)->format('H:i') : null,
                'mode' => $this->santri->mode,
                'location' => $this->santri->mode == 'offline' ? $this->santri->lokasi_offline : $this->santri->link_online,
            ],
            'pengumuman_hasil' => [
                'completed' => in_array($this->santri->status_santri, ['diterima', 'ditolak']),
                'current' => false,
                'status' => $this->santri->status_santri,
                'date' => $this->santri->updated_at && in_array($this->santri->status_santri, ['diterima', 'ditolak']) 
                    ? $this->santri->updated_at->format('d F Y') 
                    : null,
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
        return redirect()->route('login-ppdb-santri');
    }

    public function render()
    {
        return view('livewire.guest.check-status', [
            'title' => 'Cek Status Pendaftaran'
        ])->extends('components.layouts.check-status');
    }
}