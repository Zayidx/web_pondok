<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;                  // Mengimpor kelas utama Livewire
use App\Models\PSB\PendaftaranSantri;    // Mengimpor model untuk data pendaftaran santri
use App\Models\PSB\WaliSantri;           // Mengimpor model untuk data wali santri
use App\Models\PSB\Dokumen;              // Mengimpor model untuk data dokumen

class DetailRegistration extends Component
{
    public $santri;         // Variabel untuk menyimpan data santri
    public $wali;           // Variabel untuk menyimpan data wali santri
    public $dokumen;        // Variabel untuk menyimpan koleksi dokumen santri
    public $fotoSantri;     // Variabel untuk menyimpan path foto santri dari dokumen
    public $formPage = 1;   // Variabel untuk melacak halaman formulir (default: 1)

    /**
     * Menginisialisasi data saat komponen dimuat
     * - Mengambil data santri berdasarkan ID dari URL
     * - Mengambil data wali dan dokumen terkait
     * - Menyimpan path foto santri jika ada dokumen dengan jenis 'foto'
     */
    public function mount($santriId)
    {
        $this->santri = PendaftaranSantri::with(['dokumen'])->findOrFail($santriId);  // Mengambil data santri dengan relasi dokumen
        $this->wali = WaliSantri::where('pendaftaran_santri_id', $santriId)->firstOrFail();  // Mengambil data wali
        $this->dokumen = $this->santri->dokumen ?? collect();  // Mengambil dokumen atau koleksi kosong jika tidak ada

        // Mencari dokumen dengan jenis 'foto' dan menyimpan path-nya
        $this->fotoSantri = $this->dokumen->where('jenis_berkas', 'foto')->first()?->file_path;
    }

    /**
     * Bergerak ke halaman formulir berikutnya
     * - Meningkatkan nomor halaman jika belum mencapai batas (maksimal 3)
     */
    public function nextForm()
    {
        if ($this->formPage < 3) {
            $this->formPage++;
        }
    }

    /**
     * Bergerak ke halaman formulir sebelumnya
     * - Mengurangi nomor halaman jika belum di halaman pertama
     */
    public function prevForm()
    {
        if ($this->formPage > 1) {
            $this->formPage--;
        }
    }

    /**
     * Menampilkan halaman detail pendaftaran
     * - Menggunakan view 'livewire.admin.psb.detail-registration'
     */
    public function render()
    {
        return view('livewire.admin.psb.detail-registration');
    }
}