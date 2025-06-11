<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\Ujian;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Barryvdh\Debugbar\Facades\Debugbar;

class MulaiUjian extends Component
{
    #[Layout('components.layouts.ujian')]
    #[Title('Mulai Ujian')]

    public $ujian;
    public $santri;
    public $hasilUjian;
    public $jumlahSoal;
    public $jawabanSiswa = [];
    public $soalDijawab = 0;
    public $isFinished = false;
    public $currentPage = 1;
    public $jawaban;
    public $modalMessage = '';
    public $showModal = false;
    public $sisaWaktuDetik;

    public function mount($ujianId)
    {
        $this->santri = Auth::guard('santri')->user() ?? PendaftaranSantri::find(session('santri_id'));

        if (!$this->santri) {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        $this->ujian = Ujian::withCount('soals')->findOrFail($ujianId);
        $this->jumlahSoal = $this->ujian->soals_count;

        $this->hasilUjian = HasilUjian::firstOrCreate(
            ['santri_id' => $this->santri->id, 'ujian_id' => $this->ujian->id],
            [
                'status' => 'sedang_mengerjakan',
                'waktu_mulai' => now(),
                'waktu_selesai' => Carbon::parse($this->ujian->tanggal_ujian->format('Y-m-d') . ' ' . $this->ujian->waktu_selesai)
            ]
        );

        if ($this->hasilUjian->status === 'selesai') {
            session()->flash('message', 'Anda sudah menyelesaikan ujian ini.');
            return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
        }
        
        $waktuSelesai = Carbon::parse($this->hasilUjian->waktu_selesai);
        $this->sisaWaktuDetik = now()->diffInSeconds($waktuSelesai, false);
        if ($this->sisaWaktuDetik <= 0) {
            // Redirect kembali ke dasbor dengan pesan error yang jelas
            session()->flash('error', 'Waktu untuk mengerjakan ujian "' . $this->ujian->nama_ujian . '" sudah berakhir.');
            return redirect()->route('santri.dashboard-ujian');
        }
        $jawabanUjians = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get();
        foreach ($jawabanUjians as $jawaban) {
            $this->jawabanSiswa[$jawaban->soal_id] = $jawaban->jawaban;
        }
        // Kalkulasi awal progres
        $this->hitungSoalDijawab();
    }

    // Metode privat baru untuk menghitung progres secara andal
    private function hitungSoalDijawab()
    {
        $this->soalDijawab = count(array_filter($this->jawabanSiswa, function ($value) {
            // Sebuah jawaban dihitung jika tidak null dan bukan string kosong
            return $value !== null && trim((string) $value) !== '';
        }));
    }

    public function tick()
    {
        if ($this->sisaWaktuDetik > 0) {
            $this->sisaWaktuDetik--;
        } else {
            $this->submitUjian();
        }
    }

    #[Computed]
    public function waktuMundurFormatted()
    {
        $hours = floor($this->sisaWaktuDetik / 3600);
        $minutes = floor(($this->sisaWaktuDetik % 3600) / 60);
        $seconds = $this->sisaWaktuDetik % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    #[Computed]
    public function durasi()
    {
        if (!$this->ujian) return 0;
        $waktuMulai = Carbon::parse($this->ujian->waktu_mulai);
        $waktuSelesai = Carbon::parse($this->ujian->waktu_selesai);
        return $waktuMulai->diffInMinutes($waktuSelesai);
    }

    public function hapusJawaban($soalId)
    {
        if ($this->isFinished) return;
        
        // Hapus jawaban dari array lokal
        unset($this->jawabanSiswa[$soalId]);

        // Hapus jawaban dari database
        JawabanUjian::where(['hasil_ujian_id' => $this->hasilUjian->id, 'soal_id' => $soalId])->delete();

        // **FIX**: Hitung ulang progres menggunakan metode baru
        $this->hitungSoalDijawab();

        $this->dispatch('jawaban-updated', soalId: $soalId);
    }

    public function simpanJawaban($soalId, $jawaban)
    {
        // Memeriksa secara spesifik, 0 atau '0' tidak akan dianggap kosong lagi
        if ($jawaban === null || $jawaban === '') {
            return $this->hapusJawaban($soalId);
        }
        
        // Simpan jawaban ke array lokal
        $this->jawabanSiswa[$soalId] = $jawaban;
        
        // Simpan jawaban ke database
        JawabanUjian::updateOrCreate(
            ['hasil_ujian_id' => $this->hasilUjian->id, 'soal_id' => $soalId],
            ['jawaban' => $jawaban]
        );
    
        $this->hitungSoalDijawab();
        $this->dispatch('jawaban-updated', soalId: $soalId);
    }
    
    public function checkUnansweredQuestions()
    {
        $soals = $this->ujian->soals;
        $unansweredQuestions = [];
        foreach ($soals as $index => $soal) {
            if (!isset($this->jawabanSiswa[$soal->id]) || empty($this->jawabanSiswa[$soal->id])) {
                $unansweredQuestions[] = $index + 1;
            }
        }
        if (count($unansweredQuestions) > 0) {
            $this->modalMessage = 'Soal nomor ' . implode(', ', $unansweredQuestions) . ' belum dijawab. Apakah Anda yakin ingin mengumpulkan ujian?';
        } else {
            $this->modalMessage = 'Semua soal sudah dijawab. Apakah Anda yakin ingin mengumpulkan ujian?';
        }
    }

     // ... (Computed properties dan metode lain sebelum submitUjian tetap sama) ...
     public function confirmSubmit()
     {
         $this->checkUnansweredQuestions();
         $this->showModal = true;
         $this->dispatch('show-modal');
     }
 
     public function submitUjian()
     {
         if ($this->isFinished) {
             return;
         }
 
         DB::beginTransaction(); // Memulai transaksi database
         try {
             $this->isFinished = true;
 
             // =================================================================
             // **LOGIKA BARU DIMULAI DI SINI**
             // =================================================================
 
             // 1. Ambil semua jawaban yang tersimpan untuk ujian ini
             $semuaJawaban = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)
                                         ->with('soal') // Eager load data soal untuk akses poin dan kunci jawaban
                                         ->get();
 
             // 2. Hitung total poin dari jawaban PG yang benar
             $totalPoinUjianIni = 0;
             foreach ($semuaJawaban as $jawaban) {
                 // Hanya hitung soal Pilihan Ganda (PG)
                 if ($jawaban->soal && $jawaban->soal->tipe_soal === 'pg') {
                     if ($jawaban->jawaban === $jawaban->soal->kunci_jawaban) {
                         $totalPoinUjianIni += $jawaban->soal->poin;
                     }
                 }
             }
 
             // 3. Update record hasil ujian dengan status, waktu selesai, dan nilai akhir
             $this->hasilUjian->update([
                 'waktu_selesai' => now(),
                 'status'        => 'selesai',
                 'nilai_akhir'   => $totalPoinUjianIni,
             ]);
 
             // 4. Hitung ulang nilai rata-rata dari SEMUA ujian yang telah selesai
             $semuaHasilUjianSelesai = HasilUjian::where('santri_id', $this->santri->id)
                                                 ->where('status', 'selesai')
                                                 ->get();
             
             $rataRataBaru = $semuaHasilUjianSelesai->avg('nilai_akhir') ?? 0;
 
             // 5. Update status santri dan nilai rata-rata ujiannya
             $this->santri->update([
                 'status_santri'   => 'menunggu',
                 'rata_rata_ujian' => $rataRataBaru,
             ]);
             
             // =================================================================
             // **LOGIKA BARU SELESAI**
             // =================================================================
 
             DB::commit(); // Konfirmasi semua perubahan jika tidak ada error
 
             session()->flash('message', 'Ujian berhasil dikumpulkan!');
             return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
 
         } catch (\Exception $e) {
             DB::rollback(); // Batalkan semua perubahan jika terjadi error
 
             // Log error untuk developer
             // Log::error('Error saat submit ujian: ' . $e->getMessage()); 
             
             session()->flash('error', 'Terjadi kesalahan saat mengumpulkan ujian. Silakan coba lagi.');
             $this->isFinished = false; // Set kembali agar bisa mencoba submit lagi
         }
     }

    public function gotoPage($pageNumber)
    {
        if ($pageNumber >= 1 && $pageNumber <= $this->jumlahSoal) {
            $currentSoal = $this->ujian->soals()->orderBy('id')->get()[$this->currentPage - 1] ?? null;
            if ($currentSoal && !empty($this->jawabanSiswa[$currentSoal->id])) {
                $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
            }
            $this->currentPage = $pageNumber;
            $this->reset(['jawaban']);
        }
    }
    
    public function nextPage()
    {
        if ($this->currentPage < $this->jumlahSoal) {
            $this->gotoPage($this->currentPage + 1);
        }
    }
    
    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->gotoPage($this->currentPage - 1);
        }
    }

    public function render()
    {
        $soals = $this->ujian->soals()->orderBy('id')->get();
        $currentSoal = $soals[$this->currentPage - 1] ?? null;

        return view('livewire.santri-p-p-d-b.mulai-ujian', [
            'soals' => $soals,
            'currentSoal' => $currentSoal,
            'jawabanSiswa' => $this->jawabanSiswa,
            'durasi' => $this->durasi 
        ]);
    }
}