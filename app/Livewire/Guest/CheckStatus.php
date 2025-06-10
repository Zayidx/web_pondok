<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Auth;

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
        'daftar_ulang' => [
            'completed' => false,
            'current' => false,
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

        $this->timelineStatus['daftar_ulang'] = [
            'completed' => $this->santri->status_santri === 'diterima',
            'current' => $this->santri->status_santri === 'daftar_ulang',
            'date' => $this->santri->tanggal_pembayaran ? \Carbon\Carbon::parse($this->santri->tanggal_pembayaran)->format('d F Y') : null
        ];
    }

    public function getTimelineStatusProperty()
    {
        $status = $this->santri->status_santri;
        $now = now();

        return [
            'pendaftaran' => [
                'completed' => in_array($status, ['menunggu', 'wawancara', 'sedang_ujian', 'diterima', 'ditolak', 'daftar_ulang']),
                'current' => $status === 'menunggu',
                'date' => $this->santri->created_at->format('d M Y')
            ],
            'wawancara' => [
                'completed' => in_array($status, ['sedang_ujian', 'diterima', 'ditolak', 'daftar_ulang']),
                'current' => $status === 'wawancara',
                'date' => optional($this->santri->tanggal_wawancara)->format('d M Y')
            ],
            'ujian' => [
                'completed' => in_array($status, ['diterima', 'ditolak', 'daftar_ulang']),
                'current' => $status === 'sedang_ujian',
                'date' => optional($this->santri->tanggal_ujian)->format('d M Y')
            ],
            'daftar_ulang' => [
                'completed' => $status === 'diterima',
                'current' => $status === 'daftar_ulang',
                'date' => optional($this->santri->tanggal_pembayaran)->format('d M Y')
            ]
        ];
    }

    public function testClick()
    {
        Log::info('Test click triggered');
    }
    public function logout()
    {
        try {
            Log::info('Attempting to logout santri', ['santri_id' => session('santri_id')]);
            
            // Clear all session data
            session()->forget(['santri_id', 'login_time']);
            
            // Logout from auth
            if (Auth::guard('pendaftaran_santri')->check()) {
                Auth::guard('pendaftaran_santri')->logout();
            }
            
            Log::info('Logout successful, redirecting to login page');
            
            // Use JavaScript to force redirect
            $this->dispatch('redirect', url: route('login-ppdb-santri'));
        } catch (\Exception $e) {
            Log::error('Error during logout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Even if there's an error, try to redirect
            $this->dispatch('redirect', url: route('login-ppdb-santri'));
        }
    }

    public function render()
    {
        return view('livewire.guest.check-status', [
            'title' => 'Cek Status Pendaftaran'
        ])
        ->extends('components.layouts.check-status')
        ->response(function ($response) {
            $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        });
    }
}