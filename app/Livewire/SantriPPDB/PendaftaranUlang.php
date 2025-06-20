<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri; // Menggunakan model PendaftaranSantri yang Anda kirim sebelumnya
use App\Models\PSB\Pembayaran;
use App\Models\PSB\PengaturanDaftarUlang;
use App\Models\PSB\BiayaDaftarUlang;
use App\Models\PSB\Periode;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;

class PendaftaranUlang extends Component
{
    use WithFileUploads;

    #[Layout('components.layouts.auth-ppdb-daftar-ulang')]
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
        'pembayaranForm.nominal_pembayaran' => 'required|numeric|min:1|max:9999999999.99',
        'pembayaranForm.tanggal_pembayaran' => 'required|date',
        'pembayaranForm.bank_pengirim' => 'required|string',
        'pembayaranForm.nama_pengirim' => 'required|string',
        'bukti_pembayaran' => 'required|image|max:2048',
        'terms' => 'accepted',
    ];

    protected $messages = [
        'pembayaranForm.nominal_pembayaran.required' => 'Nominal transfer harus diisi.',
        'pembayaranForm.nominal_pembayaran.numeric' => 'Nominal transfer harus berupa angka.',
        'pembayaranForm.nominal_pembayaran.min' => 'Nominal transfer minimal Rp 1.',
        'pembayaranForm.nominal_pembayaran.max' => 'Nominal transfer terlalu besar.',
        'pembayaranForm.tanggal_pembayaran.required' => 'Tanggal transfer harus diisi.',
        'pembayaranForm.tanggal_pembayaran.date' => 'Format tanggal tidak valid.',
        'pembayaranForm.bank_pengirim.required' => 'Bank pengirim harus dipilih.',
        'pembayaranForm.nama_pengirim.required' => 'Nama pengirim harus diisi.',
        'bukti_pembayaran.required' => 'Bukti transfer harus diunggah.',
        'bukti_pembayaran.image' => 'File harus berupa gambar (JPG, PNG).',
        'bukti_pembayaran.max' => 'Ukuran file bukti transfer tidak boleh lebih dari 2MB.',
        'terms.accepted' => 'Anda harus menyetujui pernyataan untuk melanjutkan.',
    ];

    public function mount()
    {
        // Mengambil data santri yang sedang login dari guard 'pendaftaran_santri'
        $this->santri = auth()->guard('pendaftaran_santri')->user();
        
        // Mengambil pengaturan, biaya, dan periode yang sedang aktif
        $this->pengaturan = PengaturanDaftarUlang::where('is_active', true)->first();
        $this->biayas = BiayaDaftarUlang::where('is_active', true)->get();
        $this->periode_daftar_ulang = Periode::where('tipe_periode', 'daftar_ulang')
                                             ->where('status_periode', 'active')
                                             ->first();
        
        // Menghitung total biaya dari semua item biaya yang aktif
        $this->total_biaya = $this->biayas ? $this->biayas->sum('jumlah') : 0; 
        
        // Mengisi otomatis form nominal pembayaran dengan total biaya
        $this->pembayaranForm['nominal_pembayaran'] = $this->total_biaya;
    }

    public function nextStep()
    {
        // Validasi input form sesuai dengan halaman saat ini sebelum pindah ke halaman berikutnya
        if ($this->formPage == 1) {
            $this->validate([
                'pembayaranForm.nominal_pembayaran' => $this->rules['pembayaranForm.nominal_pembayaran'],
                'pembayaranForm.tanggal_pembayaran' => $this->rules['pembayaranForm.tanggal_pembayaran'],
                'pembayaranForm.bank_pengirim' => $this->rules['pembayaranForm.bank_pengirim'],
                'pembayaranForm.nama_pengirim' => $this->rules['pembayaranForm.nama_pengirim'],
            ]);
        } elseif ($this->formPage == 2) {
            $this->validate([
                'bukti_pembayaran' => $this->rules['bukti_pembayaran'],
            ]);
        }
        $this->formPage++;
    }

    public function previousStep()
    {
        // Kembali ke halaman form sebelumnya
        $this->formPage--;
    }

    public function submit()
    {
        Log::info('Proses submit dimulai oleh santri ID: ' . $this->santri->id);

        // [PENCEGAHAN BUG] Cek apakah sudah ada pembayaran yang menunggu verifikasi
        $existingPayment = Pembayaran::where('santri_id', $this->santri->id)
                                      ->whereIn('status_pembayaran', ['pending', 'verified'])
                                      ->exists();

        if ($existingPayment) {
            session()->flash('error', 'Anda sudah melakukan pendaftaran ulang. Mohon tunggu proses verifikasi.');
            Log::warning('Submit dibatalkan karena pembayaran sudah ada untuk santri ID: ' . $this->santri->id);
            return;
        }

        // Memvalidasi semua data form
        $this->validate();
        Log::info('Validasi berhasil.');

        try {
            // Mengunggah bukti pembayaran ke storage
            $bukti_path = $this->bukti_pembayaran->store('bukti-pembayaran', 'public');
            Log::info('Bukti pembayaran berhasil diunggah ke path: ' . $bukti_path);

            // Membuat record baru di tabel Pembayaran
            Pembayaran::create([
                'santri_id' => $this->santri->id,
                'nominal' => $this->pembayaranForm['nominal_pembayaran'],
                'tanggal_pembayaran' => $this->pembayaranForm['tanggal_pembayaran'],
                'bank_pengirim' => $this->pembayaranForm['bank_pengirim'],
                'nama_pengirim' => $this->pembayaranForm['nama_pengirim'],
                'bukti_pembayaran' => $bukti_path,
                'status_pembayaran' => 'pending', // Status awal pembayaran
            ]);
            Log::info('Data pembayaran berhasil disimpan ke database.');

            // [PERBAIKAN KRITIS] Update status di tabel santri itu sendiri
            $this->santri->update([
                'status_pembayaran' => 'pending'
            ]);
            Log::info('Status pembayaran santri ID ' . $this->santri->id . ' berhasil diupdate menjadi pending.');

            // Menampilkan modal sukses
            $this->showSuccessModal = true;
            Log::info('Proses submit selesai, menampilkan modal sukses.');

        } catch (\Exception $e) {
            // Menangkap dan mencatat error yang mungkin terjadi
            Log::error('Terjadi error saat proses submit untuk santri ID: ' . $this->santri->id, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            session()->flash('error', 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.');
        }
    }

    public function closeModal()
    {
        // Menutup modal sukses dan redirect ke halaman status
        $this->showSuccessModal = false;
        return redirect()->route('check-status');
    }

    public function render()
    {
        // Merender view blade
        return view('livewire.santri-p-p-d-b.pendaftaran-ulang');
    }
}