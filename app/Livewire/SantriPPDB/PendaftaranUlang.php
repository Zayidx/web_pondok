<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\Periode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\PSB\RincianBiaya;
use App\Models\PSB\PeriodeDaftarUlang;
use Livewire\Attributes\Rule;
use Carbon\Carbon;

class PendaftaranUlang extends Component
{
    use WithFileUploads;

    #[Layout('components.layouts.auth-ppdb')]
    #[Title('Pendaftaran Ulang Santri')]

    public $formPage = 1;
    public $showSuccessModal = false;
    public $terms = false;
    public $bukti_pembayaran;
    public $periode;
    public $santri;

    public $pembayaranForm = [
        'nominal_pembayaran' => '',
        'tanggal_pembayaran' => '',
        'bank_pengirim' => '',
        'nama_pengirim' => '',
    ];

    protected $rules = [
        'pembayaranForm.nominal_pembayaran' => 'required|numeric|min:100000',
        'pembayaranForm.tanggal_pembayaran' => 'required|date',
        'pembayaranForm.bank_pengirim' => 'required',
        'pembayaranForm.nama_pengirim' => 'required',
        'bukti_pembayaran' => 'required|image|max:2048',
        'terms' => 'accepted'
    ];

    protected $messages = [
        'pembayaranForm.nominal_pembayaran.required' => 'Nominal transfer harus diisi',
        'pembayaranForm.nominal_pembayaran.numeric' => 'Nominal transfer harus berupa angka',
        'pembayaranForm.nominal_pembayaran.min' => 'Nominal transfer minimal Rp 100.000',
        'pembayaranForm.tanggal_pembayaran.required' => 'Tanggal transfer harus diisi',
        'pembayaranForm.tanggal_pembayaran.date' => 'Format tanggal tidak valid',
        'pembayaranForm.bank_pengirim.required' => 'Bank pengirim harus dipilih',
        'pembayaranForm.nama_pengirim.required' => 'Nama pengirim harus diisi',
        'bukti_pembayaran.required' => 'Bukti transfer harus diupload',
        'bukti_pembayaran.image' => 'File harus berupa gambar',
        'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        'terms.accepted' => 'Anda harus menyetujui pernyataan ini'
    ];

    public function mount()
    {
        // Get santri from session
        $santriId = session('santri_id');
        if (!$santriId) {
            session()->flash('error', 'Sesi anda telah berakhir. Silakan login kembali.');
            return redirect()->route('login-ppdb-santri');
        }

        $this->santri = PendaftaranSantri::find($santriId);
        if (!$this->santri) {
            session()->flash('error', 'Data santri tidak ditemukan');
            return redirect()->route('login-ppdb-santri');
        }

        // Check if santri is eligible for daftar ulang
        if ($this->santri->status_santri !== 'diterima' && $this->santri->status_santri !== 'daftar_ulang') {
            session()->flash('error', 'Anda belum bisa melakukan pendaftaran ulang');
            return redirect()->route('check-status');
        }

        // If payment is verified, show verification success page
        if ($this->santri->status_pembayaran === 'verified') {
            return redirect()->route('check-status');
        }

        // If payment was rejected, load previous data for resubmission
        if ($this->santri->status_santri === 'daftar_ulang' && $this->santri->status_pembayaran === 'rejected') {
            $this->pembayaranForm = [
                'nominal_pembayaran' => $this->santri->nominal_pembayaran,
                'tanggal_pembayaran' => $this->santri->tanggal_pembayaran,
                'bank_pengirim' => $this->santri->bank_pengirim,
                'nama_pengirim' => $this->santri->nama_pengirim,
            ];
        }

        // Get active period
        $this->periode = Periode::where('status_periode', 'active')
            ->where('tipe_periode', 'daftar_ulang')
            ->first();

        if (!$this->periode) {
            session()->flash('error', 'Periode pendaftaran ulang belum dibuka');
            return redirect()->route('check-status');
        }
    }

    public function nextStep()
    {
        if ($this->formPage === 1) {
            $this->validateOnly('pembayaranForm.nominal_pembayaran');
            $this->validateOnly('pembayaranForm.tanggal_pembayaran');
            $this->validateOnly('pembayaranForm.bank_pengirim');
            $this->validateOnly('pembayaranForm.nama_pengirim');
        } elseif ($this->formPage === 2) {
            $this->validateOnly('bukti_pembayaran');
        }

        $this->formPage++;
    }

    public function previousStep()
    {
        $this->formPage--;
    }

    public function submit()
    {
        $this->validate();

        try {
            if (!$this->santri) {
                throw new \Exception('Data santri tidak ditemukan');
            }

            // Delete old bukti_pembayaran if exists and status was rejected
            if ($this->santri->status_pembayaran === 'rejected' && $this->santri->bukti_pembayaran) {
                Storage::disk('public')->delete($this->santri->bukti_pembayaran);
            }

            // Store new bukti_pembayaran
            $filename = null;
            if ($this->bukti_pembayaran) {
                $filename = $this->bukti_pembayaran->store('bukti-pembayaran', 'public');
            }

            // Update santri data
            $updateData = [
                'nominal_pembayaran' => $this->pembayaranForm['nominal_pembayaran'],
                'tanggal_pembayaran' => $this->pembayaranForm['tanggal_pembayaran'],
                'bank_pengirim' => $this->pembayaranForm['bank_pengirim'],
                'nama_pengirim' => $this->pembayaranForm['nama_pengirim'],
                'status_pembayaran' => 'pending',
                'status_santri' => 'daftar_ulang',
                'verified_by' => null,
                'verified_at' => null,
                'catatan_verifikasi' => null
            ];

            if ($filename) {
                $updateData['bukti_pembayaran'] = $filename;
            }

            $this->santri->update($updateData);

            $this->showSuccessModal = true;
            $this->resetForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showSuccessModal = false;
        return redirect()->route('check-status');
    }

    private function resetForm()
    {
        $this->pembayaranForm = [
            'nominal_pembayaran' => '',
            'tanggal_pembayaran' => '',
            'bank_pengirim' => '',
            'nama_pengirim' => '',
        ];
        $this->bukti_pembayaran = null;
        $this->terms = false;
        $this->formPage = 1;
    }

    public function render()
    {
        return view('livewire.santri-p-p-d-b.pendaftaran-ulang');
    }
}
