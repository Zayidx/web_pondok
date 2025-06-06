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

    protected $paginationTheme = 'tailwind';

    public function mount($ujianId)
    {
        // Get santri data from auth
        $this->santri = Auth::guard('santri')->user();
        if (!$this->santri) {
            $this->santri = PendaftaranSantri::find(session('santri_id'));
        }

        if (!$this->santri || $this->santri->status_santri !== 'sedang_ujian') {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        // Get exam data with soal count
        $this->ujian = Ujian::withCount('soals')->findOrFail($ujianId);
        $this->jumlahSoal = $this->ujian->soals_count;

        // Calculate duration
        $waktuMulai = Carbon::parse($this->ujian->waktu_mulai);
        $waktuSelesai = Carbon::parse($this->ujian->waktu_selesai);
        $this->durasi = $waktuMulai->diffInMinutes($waktuSelesai);

        // Create or get hasil ujian
        $this->hasilUjian = HasilUjian::firstOrCreate([
            'santri_id' => $this->santri->id,
            'ujian_id' => $this->ujian->id,
        ], [
            'waktu_mulai' => now(),
            'status' => 'sedang_mengerjakan'
        ]);

        // Load existing answers
        $jawabanUjians = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get();
        foreach ($jawabanUjians as $jawaban) {
            // For multiple choice answers, convert numeric index back to letter
            if ($jawaban->soal->tipe_soal === 'pg' && is_numeric($jawaban->jawaban)) {
                $this->jawabanSiswa[$jawaban->soal_id] = chr(65 + $jawaban->jawaban); // Convert 0-3 to A-D
            } else {
                $this->jawabanSiswa[$jawaban->soal_id] = $jawaban->jawaban;
            }
        }
        $this->soalDijawab = count($this->jawabanSiswa);

        // Set waktu mulai dan selesai
        $this->waktuMulai = $this->hasilUjian->waktu_mulai;
        $this->waktuSelesai = $this->waktuMulai->copy()->addMinutes($this->durasi);

        // Calculate remaining time
        $this->sisaWaktu = now()->diffInSeconds($this->waktuSelesai);
    }

    public function toggleAnswer($soalId, $jawaban)
    {
        if ($this->isFinished) return;

        $soal = collect($this->ujian->soals)->firstWhere('id', $soalId);
        $existingJawaban = $this->jawabanSiswa[$soalId] ?? null;

        // For multiple choice questions
        if ($soal->tipe_soal === 'pg') {
            // Convert letter to numeric index for storage
            $numericJawaban = is_string($jawaban) ? ord(strtoupper($jawaban)) - ord('A') : $jawaban;
            
            // If clicking the same answer again, clear it
            if ($existingJawaban === $jawaban) {
                unset($this->jawabanSiswa[$soalId]);
                JawabanUjian::where([
                    'hasil_ujian_id' => $this->hasilUjian->id,
                    'soal_id' => $soalId,
                ])->delete();
            } else {
                $this->jawabanSiswa[$soalId] = $jawaban; // Store letter in component state
                JawabanUjian::updateOrCreate(
                    [
                        'hasil_ujian_id' => $this->hasilUjian->id,
                        'soal_id' => $soalId,
                    ],
                    ['jawaban' => $numericJawaban] // Store numeric in database
                );
            }
        } 
        // For essay questions
        else {
            // Only save if the answer is not empty
            if (trim($jawaban) !== '') {
                $this->jawabanSiswa[$soalId] = $jawaban;
                JawabanUjian::updateOrCreate(
                    [
                        'hasil_ujian_id' => $this->hasilUjian->id,
                        'soal_id' => $soalId,
                    ],
                    ['jawaban' => $jawaban]
                );
            } else {
                unset($this->jawabanSiswa[$soalId]);
                JawabanUjian::where([
                    'hasil_ujian_id' => $this->hasilUjian->id,
                    'soal_id' => $soalId,
                ])->delete();
            }
        }

        $this->soalDijawab = count($this->jawabanSiswa);
    }

    public function simpanJawaban($soalId, $jawaban)
    {
        if ($this->isFinished) return;

        // Get the current soal to check its type
        $soal = collect($this->ujian->soals)->firstWhere('id', $soalId);

        if ($soal->tipe_soal === 'pg') {
            // For multiple choice, convert letter to numeric index
            if (is_string($jawaban) && strlen($jawaban) === 1) {
                $jawaban = ord(strtoupper($jawaban)) - ord('A');
            }
        }
        // For essay, keep the text answer as is

        // Save answer to database
        JawabanUjian::updateOrCreate(
            [
                'hasil_ujian_id' => $this->hasilUjian->id,
                'soal_id' => $soalId,
            ],
            ['jawaban' => $jawaban]
        );

        // Update local state
        $this->jawabanSiswa[$soalId] = $jawaban;
        $this->soalDijawab = count($this->jawabanSiswa);
    }

    public function checkUnansweredQuestions()
    {
        $soals = $this->ujian->soals;
        $unansweredQuestions = [];
        
        foreach ($soals as $index => $soal) {
            if (!isset($this->jawabanSiswa[$soal->id])) {
                $unansweredQuestions[] = $index + 1;
            }
        }
        
        if (count($unansweredQuestions) > 0) {
            $this->modalMessage = 'Soal nomor ' . implode(', ', $unansweredQuestions) . ' belum dijawab. Apakah Anda yakin ingin mengumpulkan ujian?';
            $this->dispatch('open-modal');
            return false;
        }
        
        $this->modalMessage = 'Apakah Anda yakin ingin mengumpulkan ujian? Pastikan semua jawaban sudah terisi dengan benar.';
        $this->dispatch('open-modal');
        return true;
    }

    public function confirmSubmit()
    {
        $this->checkUnansweredQuestions();
    }

    public function waktuHabis()
    {
        if ($this->isFinished) return;
        
        $this->isFinished = true;

        // Save current answer if exists
        $currentSoal = $this->ujian->soals[$this->currentPage - 1] ?? null;
        if ($currentSoal && isset($this->jawabanSiswa[$currentSoal->id])) {
            $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
        }

        // Update hasil ujian
        $this->hasilUjian->update([
            'waktu_selesai' => now(),
            'status' => 'selesai'
        ]);

        // Update santri status
        $this->santri->update(['status_santri' => 'menunggu']);

        return $this->redirectRoute('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
    }

    public function submitUjian()
    {
        if ($this->isFinished) return;

        $this->isFinished = true;

        // Save current answer if exists
        $currentSoal = $this->ujian->soals[$this->currentPage - 1] ?? null;
        if ($currentSoal && isset($this->jawabanSiswa[$currentSoal->id])) {
            $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
        }

        // Update hasil ujian
        $this->hasilUjian->update([
            'waktu_selesai' => now(),
            'status' => 'selesai'
        ]);

        // Update santri status
        $this->santri->update(['status_santri' => 'menunggu']);

        return $this->redirectRoute('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
    }

    public function gotoPage($pageNumber)
    {
        if ($pageNumber >= 1 && $pageNumber <= $this->jumlahSoal) {
            // Save current answer if exists
            $currentSoal = $this->ujian->soals[$this->currentPage - 1] ?? null;
            if ($currentSoal && isset($this->jawabanSiswa[$currentSoal->id])) {
                $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
            }
            
            $this->currentPage = $pageNumber;
        }
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->jumlahSoal) {
            // Save current answer if exists
            $currentSoal = $this->ujian->soals[$this->currentPage - 1] ?? null;
            if ($currentSoal && isset($this->jawabanSiswa[$currentSoal->id])) {
                $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
            }
            
            $this->currentPage++;
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            // Save current answer if exists
            $currentSoal = $this->ujian->soals[$this->currentPage - 1] ?? null;
            if ($currentSoal && isset($this->jawabanSiswa[$currentSoal->id])) {
                $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
            }
            
            $this->currentPage--;
        }
    }

    public function render()
    {
        $soals = $this->ujian->soals()
            ->orderBy('id')
            ->get();

        $currentSoal = $soals[$this->currentPage - 1] ?? null;

        return view('livewire.santri-p-p-d-b.mulai-ujian', [
            'soals' => $soals,
            'currentSoal' => $currentSoal
        ]);
    }
} 