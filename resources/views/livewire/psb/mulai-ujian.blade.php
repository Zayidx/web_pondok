<?php

namespace App\Livewire\PSB;

use Livewire\Component;
use App\Models\Ujian;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * MulaiUjian Livewire Class.
 *
 * This component manages the display and logic for the student's exam page.
 * It handles exam initialization, saving answers, managing the countdown timer,
 * and processing exam submissions.
 */
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

    /**
     * The mount function, executed when the component is initialized.
     * Retrieves student and exam data, and initializes exam results.
     *
     * @param int $ujianId The ID of the exam to start.
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function mount($ujianId)
    {
        $this->santri = Auth::guard('santri')->user() ?? PendaftaranSantri::find(session('santri_id'));

        if (!$this->santri) {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        $this->ujian = Ujian::withCount('soals')->findOrFail($ujianId);
        $this->jumlahSoal = $this->ujian->soals_count;

        $now = Carbon::now();
        $waktuMulaiUjian = Carbon::parse($this->ujian->tanggal_ujian->format('Y-m-d') . ' ' . $this->ujian->waktu_mulai);
        $waktuSelesaiUjian = Carbon::parse($this->ujian->tanggal_ujian->format('Y-m-d') . ' ' . $this->ujian->waktu_selesai);

        if ($now->lessThan($waktuMulaiUjian)) {
            session()->flash('error', 'Ujian "' . $this->ujian->nama_ujian . '" belum dimulai. Ujian akan dimulai pada ' . $waktuMulaiUjian->format('d F Y H:i') . ' WIB.');
            return redirect()->route('santri.dashboard-ujian');
        }

        if ($now->greaterThanOrEqualTo($waktuSelesaiUjian)) {
            $hasilUjian = HasilUjian::firstOrNew(['santri_id' => $this->santri->id, 'ujian_id' => $this->ujian->id]);
            
            if ($hasilUjian->exists && $hasilUjian->status !== 'selesai') {
                $hasilUjian->update([
                    'status' => 'selesai',
                    'waktu_selesai' => $waktuSelesaiUjian,
                    'nilai_akhir' => 0
                ]);
                $this->santri->update([
                    'status_santri' => 'sedang_ujian',
                ]);
            }
            elseif (!$hasilUjian->exists) {
                $hasilUjian->fill([
                    'status' => 'selesai',
                    'waktu_mulai' => $now,
                    'waktu_selesai' => $waktuSelesaiUjian,
                    'nilai_akhir' => 0
                ])->save();
                 $this->santri->update([
                    'status_santri' => 'sedang_ujian',
                ]);
            }
            session()->flash('error', 'Waktu untuk mengerjakan ujian "' . $this->ujian->nama_ujian . '" sudah berakhir pada ' . $waktuSelesaiUjian->format('d F Y H:i') . ' WIB.');
            return redirect()->route('santri.dashboard-ujian');
        }

        $this->hasilUjian = HasilUjian::firstOrCreate(
            ['santri_id' => $this->santri->id, 'ujian_id' => $this->ujian->id],
            [
                'status' => 'sedang_mengerjakan',
                'waktu_mulai' => $now,
                'waktu_selesai' => $waktuSelesaiUjian
            ]
        );

        if ($this->hasilUjian->status === 'selesai') {
            session()->flash('message', 'Anda sudah menyelesaikan ujian ini.');
            return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
        }
        
        $waktuSelesaiDariHasilUjian = Carbon::parse($this->hasilUjian->waktu_selesai);
        $this->sisaWaktuDetik = $now->diffInSeconds($waktuSelesaiDariHasilUjian, false);

        if ($this->sisaWaktuDetik <= 0) {
            $this->submitUjian();
            session()->flash('error', 'Waktu untuk mengerjakan ujian "' . $this->ujian->nama_ujian . '" sudah berakhir.');
            return redirect()->route('santri.dashboard-ujian');
        }

        $jawabanUjians = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get();
        foreach ($jawabanUjians as $jawaban) {
            $this->jawabanSiswa[$jawaban->soal_id] = $jawaban->jawaban;
        }

        $this->hitungSoalDijawab();
    }

    private function hitungSoalDijawab()
    {
        $this->soalDijawab = count(array_filter($this->jawabanSiswa, function ($value) {
            return $value !== null && trim((string) $value) !== '';
        }));
    }

    public function tick()
    {
        $now = Carbon::now();
        $waktuSelesaiUjianTerjadwal = Carbon::parse($this->ujian->tanggal_ujian->format('Y-m-d') . ' ' . $this->ujian->waktu_selesai);

        if ($now->greaterThanOrEqualTo($waktuSelesaiUjianTerjadwal) || $this->sisaWaktuDetik <= 0) {
            $this->sisaWaktuDetik = 0;
            $this->submitUjian();
        } else {
            $this->sisaWaktuDetik--;
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
        
        unset($this->jawabanSiswa[$soalId]);

        JawabanUjian::where(['hasil_ujian_id' => $this->hasilUjian->id, 'soal_id' => $soalId])->delete();

        $this->hitungSoalDijawab();

        $this->dispatch('jawaban-updated', soalId: $soalId);
    }

    public function simpanJawaban($soalId, $jawaban)
    {
        if ($jawaban === null || $jawaban === '') {
            return $this->hapusJawaban($soalId);
        }
        
        $this->jawabanSiswa[$soalId] = $jawaban;
        
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

    public function confirmSubmit()
    {
        $this->checkUnansweredQuestions();
        $this->showModal = true;
        $this->dispatch('show-modal');
        $this->gotoPage($this->currentPage + 1);
    }
 
    public function submitUjian()
    {
        foreach ($this->jawabanSiswa as $soalId => $jawaban) {
            $this->simpanJawaban($soalId, $jawaban);
        }

        $totalScore = 0;
        $soals = $this->ujian->soals;
        
        Log::info('Starting score calculation', [
            'ujian_id' => $this->ujian->id,
            'santri_id' => $this->santri->id,
            'total_soal' => count($soals)
        ]);
        
        foreach ($soals as $soal) {
            $jawaban = $this->jawabanSiswa[$soal->id] ?? null;
            
            if ($soal->tipe_soal === 'pg' && $jawaban !== null) {
                $answerIndex = (int)$jawaban;
                
                Log::info('Processing PG answer', [
                    'soal_id' => $soal->id,
                    'jawaban' => $jawaban,
                    'answerIndex' => $answerIndex,
                    'opsi' => $soal->opsi
                ]);
                
                if (isset($soal->opsi[$answerIndex]['bobot'])) {
                    $poinPG = (float)$soal->opsi[$answerIndex]['bobot'];
                    $totalScore += $poinPG;
                    
                    Log::info('PG score calculated', [
                        'soal_id' => $soal->id,
                        'poinPG' => $poinPG,
                        'totalScore' => $totalScore
                    ]);
                    
                    $jawabanUjian = JawabanUjian::where([
                        'hasil_ujian_id' => $this->hasilUjian->id,
                        'soal_id' => $soal->id
                    ])->first();

                    if ($jawabanUjian) {
                        $jawabanUjian->update([
                            'nilai' => $poinPG,
                            'jawaban' => $jawaban
                        ]);
                        
                        Log::info('Jawaban updated', [
                            'soal_id' => $soal->id,
                            'nilai' => $poinPG,
                            'jawaban' => $jawaban
                        ]);
                    }
                }
            } elseif ($soal->tipe_soal === 'essay' && $jawaban !== null) {
                if (isset($soal->bobot)) {
                    $poinEssay = (float)$soal->bobot;
                    $totalScore += $poinEssay;
                    
                    Log::info('Essay score calculated', [
                        'soal_id' => $soal->id,
                        'poinEssay' => $poinEssay,
                        'totalScore' => $totalScore
                    ]);
                    
                    $jawabanUjian = JawabanUjian::where([
                        'hasil_ujian_id' => $this->hasilUjian->id,
                        'soal_id' => $soal->id
                    ])->first();

                    if ($jawabanUjian) {
                        $jawabanUjian->update([
                            'nilai' => $poinEssay,
                            'jawaban' => $jawaban
                        ]);
                        
                        Log::info('Essay answer updated', [
                            'soal_id' => $soal->id,
                            'nilai' => $poinEssay,
                            'jawaban' => $jawaban
                        ]);
                    }
                }
            }
        }

        Log::info('Final score calculation', [
            'totalScore' => $totalScore,
            'hasilUjianId' => $this->hasilUjian->id
        ]);

        $this->hasilUjian->update([
            'nilai_akhir' => $totalScore,
            'status' => 'selesai',
            'waktu_selesai' => now()
        ]);

        $this->santri->update([
            'status_santri' => 'selesai_ujian' // Ubah status santri menjadi 'selesai_ujian'
        ]);

        $semuaHasilUjian = HasilUjian::where('santri_id', $this->santri->id)
            ->where('status', 'selesai')
            ->get();

        $totalNilaiSemuaUjian = $semuaHasilUjian->sum('nilai_akhir');
        $rataRataUjian = $semuaHasilUjian->avg('nilai_akhir');

        $this->santri->update([
            'total_nilai_semua_ujian' => $totalNilaiSemuaUjian,
            'rata_rata_ujian' => $rataRataUjian
        ]);

        return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
    }

    public function gotoPage($pageNumber)
    {
        if ($pageNumber >= 1 && $pageNumber <= $this->jumlahSoal) {
            // Ubah baris ini untuk menerapkan pengurutan
            $soals = $this->ujian->soals()
                        ->orderByRaw("CASE WHEN tipe_soal = 'pg' THEN 0 ELSE 1 END")
                        ->orderBy('id', 'asc') // Tambahkan pengurutan sekunder berdasarkan ID
                        ->get();
            $currentSoal = $soals[$this->currentPage - 1] ?? null;

            if ($currentSoal && isset($this->jawabanSiswa[$currentSoal->id])) {
                $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
            }
            
            $this->currentPage = $pageNumber;
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
        // Ubah baris ini untuk menerapkan pengurutan
        $soals = $this->ujian->soals()
                    ->orderByRaw("CASE WHEN tipe_soal = 'pg' THEN 0 ELSE 1 END")
                    ->orderBy('id', 'asc') // Tambahkan pengurutan sekunder berdasarkan ID
                    ->get();
        $currentSoal = $soals[$this->currentPage - 1] ?? null;

        return view('livewire.psb.mulai-ujian', [
            'soals' => $soals,
            'currentSoal' => $currentSoal,
            'jawabanSiswa' => $this->jawabanSiswa,
            'durasi' => $this->durasi
        ]);
    }
}