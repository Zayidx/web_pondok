<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\Santri;
use App\Models\PSB\Pembayaran;
use App\Models\PSB\DaftarUlangSetting;
use App\Models\PSB\BiayaDaftarUlang;
use App\Models\PSB\PeriodeDaftarUlang;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\PSB\RincianBiaya;
use Livewire\Attributes\Rule;
use Carbon\Carbon;

class PendaftaranUlang extends Component
{
    use WithFileUploads;

    #[Layout('components.layouts.auth-ppdb')]
    #[Title('Pendaftaran Ulang Santri')]

    public $santri;
    public $formPage = 1;
    public $bukti_pembayaran;
    public $terms = false;
    public $showSuccessModal = false;
    public $pengaturan;
    public $biayas;
    public $total_biaya;
    public $periode_daftar_ulang;

    public $pembayaranForm = [
        'nominal_pembayaran' => '',
        'tanggal_pembayaran' => '',
        'bank_pengirim' => '',
        'nama_pengirim' => '',
    ];

    protected $rules = [
        'pembayaranForm.nominal_pembayaran' => 'required|numeric|min:1',
        'pembayaranForm.tanggal_pembayaran' => 'required|date',
        'pembayaranForm.bank_pengirim' => 'required',
        'pembayaranForm.nama_pengirim' => 'required',
        'bukti_pembayaran' => 'required|image|max:2048',
        'terms' => 'accepted',
    ];

    protected $messages = [
        'pembayaranForm.nominal_pembayaran.required' => 'Nominal transfer harus diisi',
        'pembayaranForm.nominal_pembayaran.numeric' => 'Nominal transfer harus berupa angka',
        'pembayaranForm.nominal_pembayaran.min' => 'Nominal transfer minimal Rp 1',
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
        $this->santri = auth()->guard('pendaftaran_santri')->user();
        
        // Load pengaturan daftar ulang
        $this->pengaturan = DaftarUlangSetting::first();
        
        // Load biaya-biaya aktif
        $this->biayas = BiayaDaftarUlang::where('is_active', true)->get();
        
        // Hitung total biaya
        $this->total_biaya = $this->biayas->sum('nominal');
        
        // Load periode daftar ulang
        $this->periode_daftar_ulang = PeriodeDaftarUlang::where('is_active', true)->first();
        
        // Set default nominal pembayaran
        $this->pembayaranForm['nominal_pembayaran'] = $this->total_biaya;
    }

    public function nextStep()
    {
        if ($this->formPage == 1) {
            $this->validate([
                'pembayaranForm.nominal_pembayaran' => 'required|numeric|min:1',
                'pembayaranForm.tanggal_pembayaran' => 'required|date',
                'pembayaranForm.bank_pengirim' => 'required',
                'pembayaranForm.nama_pengirim' => 'required',
            ]);
        } elseif ($this->formPage == 2) {
            $this->validate([
                'bukti_pembayaran' => 'required|image|max:2048',
            ]);
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

        // Upload bukti pembayaran
        $bukti_path = $this->bukti_pembayaran->store('public/bukti-pembayaran');

        // Simpan data pembayaran
        Pembayaran::create([
            'santri_id' => $this->santri->id,
            'nominal' => $this->pembayaranForm['nominal_pembayaran'],
            'tanggal_pembayaran' => $this->pembayaranForm['tanggal_pembayaran'],
            'bank_pengirim' => $this->pembayaranForm['bank_pengirim'],
            'nama_pengirim' => $this->pembayaranForm['nama_pengirim'],
            'bukti_pembayaran' => $bukti_path,
            'status_pembayaran' => 'pending',
        ]);

        // Update status santri
        $this->santri->update([
            'status_pembayaran' => 'pending',
            'tanggal_pembayaran' => now(),
        ]);

        $this->showSuccessModal = true;
    }

    public function closeModal()
    {
        $this->showSuccessModal = false;
        return redirect()->route('check-status');
    }

    public function render()
    {
        return view('livewire.santri-p-p-d-b.pendaftaran-ulang');
    }
}
