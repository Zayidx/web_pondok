<?php

namespace App\Livewire\PPDB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Auth;

class CheckStatus extends Component
{
    public $santri;
    public $statusMessages = [
        'menunggu' => 'Pendaftaran Anda sedang dalam proses verifikasi.',
        'wawancara' => 'Anda terjadwal untuk wawancara.',
        'sedang_ujian' => 'Anda sedang dalam proses ujian.',
        'diterima' => 'Selamat! Anda diterima. Silakan melakukan pendaftaran ulang.',
        'ditolak' => 'Maaf, pendaftaran Anda tidak diterima.',
        'daftar_ulang' => [
            'pending' => 'Pendaftaran ulang Anda sedang dalam proses verifikasi. Silakan tunggu konfirmasi dari admin.',
            'verified' => 'Selamat! Pendaftaran ulang Anda telah diverifikasi.',
            'rejected' => 'Maaf, pendaftaran ulang Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.'
        ]
    ];

    public function mount()
    {
        if (Auth::guard('pendaftaran_santri')->check()) {
            $this->santri = Auth::guard('pendaftaran_santri')->user();
        } else {
            return redirect()->route('login-ppdb-santri');
        }
    }

    public function daftarUlang()
    {
        if ($this->santri && $this->santri->status_santri === 'diterima') {
            return redirect()->route('daftar-ulang', ['id' => $this->santri->id]);
        }
        session()->flash('error', 'Anda belum bisa melakukan pendaftaran ulang.');
    }

    public function getStatusMessageProperty()
    {
        if ($this->santri->status_santri === 'daftar_ulang') {
            $paymentStatus = $this->santri->status_pembayaran ?? 'pending';
            return $this->statusMessages['daftar_ulang'][$paymentStatus];
        }
        
        return $this->statusMessages[$this->santri->status_santri] ?? 'Status tidak diketahui';
    }

    public function render()
    {
        return view('livewire.ppdb.check-status', [
            'statusMessage' => $this->getStatusMessageProperty()
        ])->layout('components.layouts.auth-ppdb');
    }
} 