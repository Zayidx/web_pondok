<?php

namespace App\Livewire\PPDB;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\RincianBiaya;
use App\Models\PSB\RekeningSettings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DashboardDaftarUlang extends Component
{
    use WithFileUploads;

    public $santri;
    public $rekeningList;
    public $rincianBiaya;
    public $buktiPembayaran;
    public $bankPengirim;
    public $namaPengirim;
    public $tanggalPembayaran;
    public $nominalPembayaran;
    public $successMessage;
    public $errorMessage;

    protected $rules = [
        'buktiPembayaran' => 'required|image|max:2048',
        'bankPengirim' => 'required|string',
        'namaPengirim' => 'required|string',
        'tanggalPembayaran' => 'required|date',
        'nominalPembayaran' => 'required|numeric|min:1'
    ];

    public function mount($id = null)
    {
        if (!$id) {
            $this->santri = Auth::guard('pendaftaran_santri')->user();
        } else {
            $this->santri = PendaftaranSantri::findOrFail($id);
        }

        // Check if student can access registration
        if (!$this->santri) {
            session()->flash('error', 'Santri tidak ditemukan.');
            return redirect()->route('check-status');
        }

        // If student has already submitted registration and waiting for verification
        if ($this->santri->status_santri === 'daftar_ulang' && $this->santri->status_pembayaran === 'pending') {
            session()->flash('info', 'Anda telah melakukan pendaftaran ulang. Silakan tunggu verifikasi dari admin.');
            return redirect()->route('check-status');
        }

        // If student's registration has been verified
        if ($this->santri->status_santri === 'daftar_ulang' && $this->santri->status_pembayaran === 'verified') {
            session()->flash('success', 'Pendaftaran ulang Anda telah diverifikasi.');
            return redirect()->route('check-status');
        }

        // If student's registration has been rejected
        if ($this->santri->status_pembayaran === 'rejected') {
            session()->flash('error', 'Pendaftaran ulang Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.');
            return redirect()->route('check-status');
        }

        // If student is not in 'diterima' status
        if ($this->santri->status_santri !== 'diterima') {
            session()->flash('error', 'Anda belum bisa melakukan pendaftaran ulang.');
            return redirect()->route('check-status');
        }

        $this->rekeningList = RekeningSettings::where('is_active', true)->get();
        $this->rincianBiaya = RincianBiaya::where('is_active', true)
            ->where('tahun_ajaran', $this->santri->periode->tahun_ajaran)
            ->get();
    }

    public function submit()
    {
        $this->validate();

        try {
            $path = $this->buktiPembayaran->store('bukti-pembayaran', 'public');

            $this->santri->update([
                'bukti_pembayaran' => $path,
                'bank_pengirim' => $this->bankPengirim,
                'nama_pengirim' => $this->namaPengirim,
                'tanggal_pembayaran' => $this->tanggalPembayaran,
                'nominal_pembayaran' => $this->nominalPembayaran,
                'status_pembayaran' => 'pending',
                'status_santri' => 'daftar_ulang'
            ]);

            session()->flash('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi.');
            
            // Redirect to check-status after successful submission
            return redirect()->route('check-status');
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan saat mengunggah bukti pembayaran. Silakan coba lagi.';
        }
    }

    public function render()
    {
        return view('livewire.ppdb.dashboard-daftar-ulang', [
            'santri' => $this->santri,
            'rekeningList' => $this->rekeningList,
            'rincianBiaya' => $this->rincianBiaya
        ])->layout('components.layouts.auth-ppdb');
    }
} 