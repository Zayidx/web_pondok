<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\Ujian;
use App\Models\PSB\Soal;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Barryvdh\Debugbar\Facades\Debugbar;

class MulaiUjian extends Component
{
    use WithPagination;

    #[Layout('components.layouts.ujian')]
    #[Title('Mulai Ujian')]

    public $ujian;
    public $santri;
    public $hasilUjian;
    public $waktuMulai;
    public $waktuSelesai;
    public $durasi;
    public $jumlahSoal;
    public $jawabanSiswa = [];
    public $soalDijawab = 0;
    public $isFinished = false;
    public $sisaWaktu;
    public $currentPage = 1;
    public $jawaban;
    public $modalMessage = '';
    public $showWarningModal = false;
    public $showModal = false;

    protected $paginationTheme = 'tailwind';

    public function mount($ujianId)
    {
        // Get santri data from auth
        $this->santri = Auth::guard('santri')->user();
        if (!$this->santri) {
            $this->santri = PendaftaranSantri::find(session('santri_id'));
        }

        if (!$this->santri) {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        // Get exam data with soal count
        $this->ujian = Ujian::withCount('soals')->findOrFail($ujianId);
        $this->jumlahSoal = $this->ujian->soals_count;

        // Get hasil ujian
        $this->hasilUjian = HasilUjian::where('santri_id', $this->santri->id)
            ->where('ujian_id', $this->ujian->id)
            ->firstOrFail();

        // Load existing answers
        $jawabanUjians = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get();
        foreach ($jawabanUjians as $jawaban) {
            $this->jawabanSiswa[$jawaban->soal_id] = $jawaban->jawaban;
        }
        $this->soalDijawab = count(array_filter($this->jawabanSiswa, function($value) {
            return !empty($value) && trim($value) !== '';
        }));

        // Set waktu mulai dan selesai
        $this->waktuMulai = Carbon::parse($this->hasilUjian->waktu_mulai);
        $this->waktuSelesai = Carbon::parse($this->hasilUjian->waktu_selesai);
        $this->durasi = $this->waktuMulai->diffInMinutes($this->waktuSelesai);

        // Calculate remaining time
        $this->sisaWaktu = $this->waktuMulai->diffInSeconds($this->waktuSelesai);

        Debugbar::info('Hasil Ujian dibuat/diambil', [
            'santri' => $this->santri->nama_lengkap,
            'ujian' => $this->ujian->nama,
            'waktu_mulai' => $this->waktuMulai,
            'waktu_selesai' => $this->waktuSelesai,
            'sisa_waktu' => $this->sisaWaktu,
            'jawaban_siswa' => $this->jawabanSiswa
        ]);
    }

    public function hapusJawaban($soalId)
{
    if ($this->isFinished) return;

        try {
            // Update local state first for immediate UI feedback
    unset($this->jawabanSiswa[$soalId]);
            $this->jawabanSiswa = array_filter($this->jawabanSiswa, fn($value) => $value !== null && $value !== '');
            
            // Recalculate soalDijawab immediately
            $this->soalDijawab = count(array_filter($this->jawabanSiswa, function($value) {
                return !empty($value) && trim($value) !== '';
            }));

            // Delete from database
    JawabanUjian::where([
        'hasil_ujian_id' => $this->hasilUjian->id,
        'soal_id' => $soalId,
    ])->delete();

            // Dispatch UI update events
    $this->dispatch('jawaban-updated', soalId: $soalId);
    $this->dispatch('update-jawaban-siswa', jawabanSiswa: $this->jawabanSiswa);
            $this->dispatch('refresh-navigasi');
            $this->dispatch('progress-updated', progress: $this->soalDijawab);

    Debugbar::info('Jawaban dihapus', [
        'soal_id' => $soalId,
        'jawaban_siswa' => $this->jawabanSiswa,
                'soal_dijawab' => $this->soalDijawab,
                'progress_percentage' => ($this->soalDijawab / $this->jumlahSoal) * 100
    ]);
        } catch (\Exception $e) {
            Debugbar::error('Error deleting answer', [
                'error' => $e->getMessage(),
                'soal_id' => $soalId
            ]);
        }
    }

public function simpanJawaban($soalId, $jawaban)
{
        try {
            $soal = $this->ujian->soals->firstWhere('id', $soalId);
            if (!$soal) {
                throw new \Exception('Soal tidak ditemukan');
            }

            // Validasi jawaban
            if ($soal->tipe_soal === 'pg' && !in_array($jawaban, ['A', 'B', 'C', 'D'])) {
                throw new \Exception('Jawaban tidak valid');
            }

            // Jika jawaban kosong, hapus jawaban
            if (empty($jawaban)) {
                return $this->hapusJawaban($soalId);
            }

            // Update state lokal untuk UI
            $this->jawabanSiswa[$soalId] = $jawaban;
            $this->soalDijawab = count(array_filter($this->jawabanSiswa));

            // Simpan ke database
    JawabanUjian::updateOrCreate(
        [
            'hasil_ujian_id' => $this->hasilUjian->id,
            'soal_id' => $soalId,
        ],
        ['jawaban' => $jawaban]
    );

            // Update UI
    $this->dispatch('update-jawaban-siswa', jawabanSiswa: $this->jawabanSiswa);
            $this->dispatch('refresh-navigasi');
            $this->dispatch('progress-updated', progress: $this->soalDijawab);

        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }
}

    public function checkUnansweredQuestions()
    {
        Debugbar::startMeasure('check_unanswered', 'Memeriksa soal yang belum dijawab');
        
        $soals = $this->ujian->soals;
        $unansweredQuestions = [];
        
        foreach ($soals as $index => $soal) {
            if (!isset($this->jawabanSiswa[$soal->id])) {
                $unansweredQuestions[] = $index + 1;
            }
        }
        
        Debugbar::info('Status soal yang belum dijawab', [
            'total_soal' => count($soals),
            'soal_dijawab' => $this->soalDijawab,
            'soal_belum_dijawab' => count($unansweredQuestions),
            'nomor_soal_belum_dijawab' => $unansweredQuestions
        ]);
        
        if (count($unansweredQuestions) > 0) {
            $this->modalMessage = 'Soal nomor ' . implode(', ', $unansweredQuestions) . ' belum dijawab. Apakah Anda yakin ingin mengumpulkan ujian?';
            Debugbar::info('Menampilkan modal peringatan soal belum dijawab', [
                'message' => $this->modalMessage
            ]);
        } else {
            $this->modalMessage = 'Semua soal sudah dijawab. Apakah Anda yakin ingin mengumpulkan ujian?';
            Debugbar::info('Menampilkan modal konfirmasi pengumpulan', [
                'message' => $this->modalMessage
            ]);
        }
        
        Debugbar::stopMeasure('check_unanswered');
        return count($unansweredQuestions) === 0;
    }

    public function confirmSubmit()
    {
        Debugbar::startMeasure('confirm_submit', 'Proses konfirmasi submit ujian');
        
        Debugbar::info('Memulai konfirmasi submit', [
            'waktu' => now()->format('Y-m-d H:i:s'),
            'santri' => $this->santri->nama_lengkap,
            'ujian' => $this->ujian->nama_ujian,
            'total_soal' => $this->jumlahSoal,
            'soal_dijawab' => $this->soalDijawab
        ]);
    
        $this->checkUnansweredQuestions();
        $this->showModal = true;
        
        $this->dispatch('show-modal');
        
        Debugbar::stopMeasure('confirm_submit');
    }

    public function waktuHabis()
    {
        if ($this->isFinished) return;
        
        $this->submitUjian();
    }

    public function submitUjian()
    {
        try {
            Debugbar::startMeasure('submit_ujian', 'Proses Submit Ujian');
            
            $waktuMulaiSubmit = now();
            Debugbar::info('Memulai proses submit ujian', [
                'waktu_mulai_submit' => $waktuMulaiSubmit->format('Y-m-d H:i:s'),
                'santri_id' => $this->santri->id,
                'ujian_id' => $this->ujian->id,
                'total_soal' => $this->jumlahSoal,
                'soal_dijawab' => $this->soalDijawab,
                'status_awal_santri' => $this->santri->status_santri,
                'status_awal_ujian' => $this->hasilUjian->status
            ]);

            if ($this->isFinished) {
                Debugbar::warning('Ujian sudah selesai, mencegah submit ganda');
                return;
            }

            $this->isFinished = true;

            // Save current answer if exists
            $currentSoal = $this->ujian->soals[$this->currentPage - 1] ?? null;
            if ($currentSoal && isset($this->jawabanSiswa[$currentSoal->id])) {
                Debugbar::info('Menyimpan jawaban terakhir', [
                    'soal_id' => $currentSoal->id,
                    'nomor_soal' => $this->currentPage
                ]);
                $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
            }

            // Update hasil ujian
            Debugbar::info('Mengupdate status hasil ujian');
            $oldStatus = $this->hasilUjian->status;
            
            $this->hasilUjian->update([
                'waktu_selesai' => now(),
                'status' => 'selesai'
            ]);

            Debugbar::info('Status hasil ujian berubah', [
                'dari' => $oldStatus,
                'menjadi' => 'selesai',
                'waktu_perubahan' => now()->format('Y-m-d H:i:s')
            ]);

            // Update santri status
            Debugbar::info('Mengupdate status santri');
            $oldSantriStatus = $this->santri->status_santri;
            
            $this->santri->update([
                'status_santri' => 'menunggu'
            ]);

            Debugbar::info('Status santri berubah', [
                'dari' => $oldSantriStatus,
                'menjadi' => 'menunggu',
                'waktu_perubahan' => now()->format('Y-m-d H:i:s')
            ]);

            $waktuSelesaiSubmit = now();
            $durasiPengerjaan = $waktuSelesaiSubmit->diffInMinutes($this->waktuMulai);
            
            Debugbar::info('Proses submit ujian selesai', [
                'waktu_mulai_submit' => $waktuMulaiSubmit->format('Y-m-d H:i:s'),
                'waktu_selesai_submit' => $waktuSelesaiSubmit->format('Y-m-d H:i:s'),
                'durasi_pengerjaan' => $durasiPengerjaan . ' menit',
                'total_soal_dijawab' => $this->soalDijawab,
                'status_akhir_santri' => 'menunggu',
                'status_akhir_ujian' => 'selesai'
            ]);
            
            Debugbar::stopMeasure('submit_ujian');
            
            session()->flash('message', 'Ujian berhasil dikumpulkan!');
            return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);

        } catch (\Exception $e) {
            Debugbar::error('Error saat submit ujian', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'waktu_error' => now()->format('Y-m-d H:i:s')
            ]);

            session()->flash('error', 'Terjadi kesalahan saat mengumpulkan ujian. Silakan coba lagi.');
            $this->isFinished = false;
        }
    }

    public function gotoPage($pageNumber)
    {
        if ($pageNumber >= 1 && $pageNumber <= $this->jumlahSoal) {
            // Save current answer if exists
            $currentSoal = $this->ujian->soals[$this->currentPage - 1] ?? null;
            if ($currentSoal && !empty($this->jawabanSiswa[$currentSoal->id])) {
                $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
            }
            
            $this->currentPage = $pageNumber;
            $this->reset(['jawaban']);
            
            Debugbar::info('Pindah ke halaman', [
                'page_number' => $pageNumber,
                'current_soal_id' => $this->ujian->soals[$pageNumber - 1]->id ?? null,
                'jawaban_siswa' => $this->jawabanSiswa
            ]);
        }
    }
    
    public function nextPage()
    {
        if ($this->currentPage < $this->jumlahSoal) {
            // Save current answer if exists
            $currentSoal = $this->ujian->soals[$this->currentPage - 1] ?? null;
            if ($currentSoal && !empty($this->jawabanSiswa[$currentSoal->id])) {
                $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
            }
            
            $this->currentPage++;
            $this->reset(['jawaban']);
            
            Debugbar::info('Pindah ke halaman berikutnya', [
                'current_page' => $this->currentPage,
                'current_soal_id' => $this->ujian->soals[$this->currentPage - 1]->id ?? null,
                'jawaban_siswa' => $this->jawabanSiswa
            ]);
        }
    }
    
    public function previousPage()
    {
        if ($this->currentPage > 1) {
            // Save current answer if exists
            $currentSoal = $this->ujian->soals[$this->currentPage - 1] ?? null;
            if ($currentSoal && !empty($this->jawabanSiswa[$currentSoal->id])) {
                $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
            }
            
            $this->currentPage--;
            $this->reset(['jawaban']);
            
            Debugbar::info('Pindah ke halaman sebelumnya', [
                'current_page' => $this->currentPage,
                'current_soal_id' => $this->ujian->soals[$this->currentPage - 1]->id ?? null,
                'jawaban_siswa' => $this->jawabanSiswa
            ]);
        }
    }

    public function render()
    {
        $soals = $this->ujian->soals()
            ->orderBy('id')
            ->get();
        
        $currentSoal = $soals[$this->currentPage - 1] ?? null;
        
        Debugbar::info('Render halaman ujian', [
            'current_page' => $this->currentPage,
            'soal_dijawab' => $this->soalDijawab,
            'sisa_waktu' => $this->sisaWaktu,
            'current_soal_id' => $currentSoal ? $currentSoal->id : null,
            'jawaban_siswa' => $this->jawabanSiswa
        ]);
        
        return view('livewire.santri-p-p-d-b.mulai-ujian', [
            'soals' => $soals,
            'currentSoal' => $currentSoal,
            'jawabanSiswa' => $this->jawabanSiswa
        ]);
    }
} 