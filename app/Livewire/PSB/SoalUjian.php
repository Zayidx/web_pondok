<?php

namespace App\Livewire\PSB;

use Livewire\Component;
use App\Models\PSB\Ujian; // Perbarui import menjadi model di namespace PSB
use App\Models\PSB\HasilUjian; // Perbarui import menjadi model di namespace PSB
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\JawabanUjian; // Tambahkan import model JawabanUjian
use App\Models\PSB\Soal; // Tambahkan import model Soal
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB; // Tambahkan import DB
use Illuminate\Support\Collection; // Pastikan ini sudah ada
use Illuminate\Support\Facades\Log;

class SoalUjian extends Component
{
    #[Layout('components.layouts.ujian')]
    #[Title('Soal Ujian')]

    public $ujian;
    public $santri;
    public $hasilUjian;
    public $currentSoalIndex = 0; // Menggunakan index, bukan $currentSoal
    public $jawaban = []; // Array untuk menyimpan jawaban santri, key: soal_id, value: jawaban_santri
    public $waktuMulai;
    public $waktuSelesai;
    public $sisa_waktu;
    public $soals; // Properti untuk menyimpan koleksi soal ujian
    public $belumDijawab; // Properti untuk menampilkan jumlah soal yang belum dijawab

    protected $listeners = ['timeUp' => 'handleTimeUp']; // Perbarui listener untuk Livewire 3

    public function mount($ujianId)
    {
        $this->santri = Auth::guard('santri')->user();
        if (!$this->santri) {
            $this->santri = PendaftaranSantri::find(session('santri_id'));
        }

        if (!$this->santri || $this->santri->status_santri !== 'sedang_ujian') {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        $this->ujian = Ujian::with('soals')->findOrFail($ujianId); // Muat relasi soals
        $this->soals = $this->ujian->soals->sortBy('id')->values(); // Pastikan soal terurut dan dijadikan koleksi bernomor
        
        // Memuat hasil ujian yang sudah ada atau membuat yang baru
        $this->hasilUjian = HasilUjian::firstOrCreate(
            ['santri_id' => $this->santri->id, 'ujian_id' => $this->ujian->id],
            [
                'waktu_mulai' => now(),
                'status' => 'dimulai'
            ]
        );

        // Inisialisasi jawaban yang sudah ada dari database jika santri sebelumnya sudah menjawab
        $existingJawaban = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)
                                        ->get()
                                        ->keyBy('soal_id');
        foreach ($this->soals as $soal) {
            if ($existingJawaban->has($soal->id)) {
                $this->jawaban[$soal->id] = $existingJawaban[$soal->id]->jawaban;
            } else {
                $this->jawaban[$soal->id] = null; // Inisialisasi jawaban kosong
            }
        }

        $this->waktuMulai = $this->hasilUjian->waktu_mulai;
        $this->waktuSelesai = $this->waktuMulai->addMinutes($this->ujian->durasi);
        $this->hitungSisaWaktu();
        $this->updateUnansweredCount(); // Hitung soal yang belum dijawab saat mount
    }

    // Metode untuk memperbarui jawaban santri
    public function updateJawaban($soalId, $jawaban)
    {
        $this->jawaban[$soalId] = $jawaban;
        // Opsional: simpan jawaban ke database setiap kali diubah (realtime save)
        // Jika tidak, penyimpanan akan dilakukan saat submitUjian
        JawabanUjian::updateOrCreate(
            [
                'hasil_ujian_id' => $this->hasilUjian->id,
                'soal_id' => $soalId,
            ],
            [
                'jawaban' => $jawaban,
                'nilai' => null, // Nilai akan dihitung saat submit atau dinilai manual untuk essay
                'komentar' => null,
            ]
        );
        $this->updateUnansweredCount();
    }

    // Metode untuk menghitung mundur waktu
    public function hitungSisaWaktu()
    {
        $sekarang = now();
        if ($sekarang->greaterThanOrEqualTo($this->waktuSelesai)) {
            $this->sisa_waktu = 0;
            $this->handleTimeUp();
        } else {
            $diffSeconds = $this->waktuSelesai->diffInSeconds($sekarang);
            $this->sisa_waktu = $diffSeconds;
            // dispatch event setiap detik untuk update timer di frontend (opsional, tergantung implementasi timer)
            $this->dispatch('update-timer', $this->sisa_waktu);
        }
    }

    // Metode untuk pindah ke soal berikutnya
    public function nextSoal()
    {
        if ($this->currentSoalIndex < $this->soals->count() - 1) {
            $this->currentSoalIndex++;
        }
    }

    // Metode untuk kembali ke soal sebelumnya
    public function prevSoal()
    {
        if ($this->currentSoalIndex > 0) {
            $this->currentSoalIndex--;
        }
    }

    // Metode untuk langsung menuju soal tertentu
    public function goToSoal($index)
    {
        if ($index >= 0 && $index < $this->soals->count()) {
            $this->currentSoalIndex = $index;
        }
    }

    // Metode untuk menghitung jumlah soal yang belum dijawab
    public function updateUnansweredCount()
    {
        $totalSoal = $this->soals->count();
        $soalDijawab = 0;
        foreach ($this->soals as $soal) {
            // Jika ada jawaban dan tidak kosong
            if (isset($this->jawaban[$soal->id]) && $this->jawaban[$soal->id] !== null && $this->jawaban[$soal->id] !== '') {
                $soalDijawab++;
            }
        }
        $this->belumDijawab = $totalSoal - $soalDijawab;
    }

    // Metode untuk memeriksa apakah ada soal yang belum dijawab sebelum submit
    public function checkUnfinishedQuestions()
    {
        $this->updateUnansweredCount(); // Pastikan hitungan terbaru
        if ($this->belumDijawab > 0) {
            // Menggunakan Livewire dispatch untuk menampilkan konfirmasi di frontend
            $this->dispatch('show-confirm-finish', $this->belumDijawab);
        } else {
            $this->submitUjian();
        }
    }

    // Metode utama untuk menyelesaikan ujian dan menyimpan semua data
    public function submitUjian()
    {
        try {
            DB::transaction(function () {
                $totalSkorPG = 0;
                $totalSoalUjian = $this->soals->count();

                foreach ($this->soals as $soal) {
                    $jawabanSantri = $this->jawaban[$soal->id] ?? null;
                    $skorSoal = 0;

                    if ($soal->tipe_soal === Soal::TIPE_PG) {
                        // Jika soal pilihan ganda, cek jawaban dan hitung skor
                        if ($jawabanSantri == $soal->kunci_jawaban) {
                            $skorSoal = $soal->poin;
                            $totalSkorPG += $soal->poin; // Akumulasi skor PG
                        }
                    }
                    // Untuk essay, skor awal adalah 0, akan dinilai oleh admin
                    // Simpan atau perbarui jawaban santri
                    JawabanUjian::updateOrCreate(
                        [
                            'hasil_ujian_id' => $this->hasilUjian->id,
                            'soal_id' => $soal->id,
                        ],
                        [
                            'jawaban' => $jawabanSantri,
                            'nilai' => $skorSoal, // Skor awal (hanya untuk PG), essay 0
                            'komentar' => null,
                        ]
                    );
                }

                // Perbarui HasilUjian
                $this->hasilUjian->update([
                    'waktu_selesai' => now(),
                    'status' => 'selesai', // Ubah status menjadi 'selesai' setelah semua jawaban tersimpan
                    'nilai' => $totalSkorPG, // Total nilai PG
                    'nilai_akhir' => $totalSkorPG, // Nilai akhir awal sama dengan PG, akan diperbarui admin
                ]);

                // Update status santri
                $this->santri->update(['status_santri' => 'menunggu_hasil_ujian']);

                // Setelah berhasil menyimpan semua, redirect
                session()->flash('success', 'Ujian Anda telah berhasil diselesaikan!');
                return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
            });
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyelesaikan ujian: ' . $e->getMessage());
            // Log the error for debugging
            Log::error('Error submitting exam: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    // Dipanggil saat waktu habis
    public function handleTimeUp()
    {
        $this->submitUjian();
    }

    public function render()
    {
        $currentSoal = $this->soals->get($this->currentSoalIndex);
        return view('livewire.psb.soal-ujian', [
            'currentSoal' => $currentSoal,
        ]);
    }
}