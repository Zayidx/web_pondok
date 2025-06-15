<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\Santri;
use App\Models\PSB\Pembayaran;
use App\Models\PSB\DaftarUlangSetting;
use App\Models\PSB\BiayaDaftarUlang;
use App\Models\PSB\PengaturanDaftarUlang;
use App\Models\PSB\Periode;
use App\Models\PSB\PeriodeDaftarUlang;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\PSB\RincianBiaya;
use Livewire\Attributes\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Import Log facade

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
        // Batas maksimal diubah menjadi 9999999999.99 untuk sesuai dengan decimal(12,2)
        'pembayaranForm.nominal_pembayaran' => 'required|numeric|min:1|max:9999999999.99', 
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
        'pembayaranForm.nominal_pembayaran.max' => 'Nominal transfer terlalu besar (maksimal 10 digit)', 
        'pembayaranForm.tanggal_pembayaran.required' => 'Tanggal transfer harus diisi',
        'pembayaranForm.tanggal_pembayaran.date' => 'Format tanggal tidak valid',
        'pembayaranForm.bank_pengirim.required' => 'Bank pengirim harus dipilih',
        'pembayaranForm.nama_pengirim.required' => 'Nama pengirim harus diisi',
        'bukti_pembayaran.required' => 'Bukti transfer harus diupload',
        'bukti_pembayaran.image' => 'File harus berupa gambar',
        'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        'terms.accepted' => 'Anda harus menyetujui pernyataan ini',
    ];

    public function mount()
    {
        Log::info('Komponen PendaftaranUlang di-mount.');
        $this->santri = auth()->guard('pendaftaran_santri')->user();
        
        // PERBAIKAN: Load data dari model yang benar
        $this->pengaturan = PengaturanDaftarUlang::where('is_active', true)->first();
        $this->biayas = BiayaDaftarUlang::where('is_active', true)->get();
        
        // PERBAIKAN: Lakukan sum() pada kolom 'jumlah'
        $this->total_biaya = $this->biayas->sum('jumlah'); 
        
        // PERBAIKAN: Gunakan model yang konsisten
        $this->periode_daftar_ulang = Periode::where('tipe_periode', 'daftar_ulang')
        ->where('status_periode', 'active')
        ->first();
            
        $this->pembayaranForm['nominal_pembayaran'] = $this->total_biaya;
        
        Log::info('Data awal dimuat untuk santri ID: ' . $this->santri->id, [
            'total_biaya' => $this->total_biaya
        ]);
    }

    public function nextStep()
    {
        // LOG: Mencatat perpindahan step
        Log::info('Pindah ke step berikutnya dari step: ' . $this->formPage);
        if ($this->formPage == 1) {
            $this->validate([
                'pembayaranForm.nominal_pembayaran' => 'required|numeric|min:1|max:9999999999.99',
                'pembayaranForm.tanggal_pembayaran' => 'required|date',
                'pembayaranForm.bank_pengirim' => 'required',
                'pembayaranForm.nama_pengirim' => 'required',
            ]);
        } elseif ($this->formPage == 2) {
            $this->validate([
                'bukti_pembayaran' => 'required|image|max:2048',
            ]);
        }
        // LOG: Validasi step berhasil
        Log::info('Validasi untuk step ' . $this->formPage . ' berhasil.');
        $this->formPage++;
    }

    public function previousStep()
    {
        // LOG: Kembali ke step sebelumnya
        Log::info('Kembali ke step sebelumnya dari step: ' . $this->formPage);
        $this->formPage--;
    }

    public function submit()
    {
        // LOG: Memulai proses submit
        Log::info('Proses submit dimulai oleh santri ID: ' . $this->santri->id);

        // LOG: Memvalidasi semua input
        Log::info('Memvalidasi semua data form...');
        $this->validate();
        Log::info('Validasi berhasil.');

        try {
            // LOG: Memulai upload bukti pembayaran
            Log::info('Mengunggah bukti pembayaran...');
            $bukti_path = $this->bukti_pembayaran->store('public/bukti-pembayaran');
            Log::info('Bukti pembayaran berhasil diunggah ke path: ' . $bukti_path);

            // LOG: Menyiapkan data untuk disimpan
            $dataPembayaran = [
                'santri_id' => $this->santri->id, // INI KUNCINYA! Menautkan ke santri yang login
            'nominal' => $this->pembayaranForm['nominal_pembayaran'],
            'tanggal_pembayaran' => $this->pembayaranForm['tanggal_pembayaran'],
            'bank_pengirim' => $this->pembayaranForm['bank_pengirim'],
            'nama_pengirim' => $this->pembayaranForm['nama_pengirim'],
            'bukti_pembayaran' => $bukti_path,
            'status_pembayaran' => 'pending', // Status awal saat submit
            ];
            Log::info('Data pembayaran yang akan disimpan:', $dataPembayaran);

            // LOG: Menyimpan data pembayaran ke database
            Pembayaran::create($dataPembayaran);
            Log::info('Data pembayaran berhasil disimpan ke database.');

            // LOG: Mengupdate status santri
            $this->santri->update([
                'tanggal_pembayaran' => now(), // Kolom ini mungkin tidak ada, sesuaikan jika perlu
            ]);
            Log::info('Status santri berhasil diupdate.');

            $this->showSuccessModal = true;
            Log::info('Proses submit selesai, menampilkan modal sukses.');

        } catch (\Exception $e) {
            // LOG: Menangkap dan mencatat error yang terjadi
            Log::error('Terjadi error saat proses submit untuk santri ID: ' . $this->santri->id, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString() // Memberikan trace lengkap untuk debug
            ]);

            // Memberi notifikasi ke pengguna (opsional)
            session()->flash('error', 'Terjadi kesalahan saat memproses data Anda. Silakan coba lagi.');
        }
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
