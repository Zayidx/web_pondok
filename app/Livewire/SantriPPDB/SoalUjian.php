<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

class SoalUjian extends Component
{
    #[Layout('components.layouts.ujian')]
    #[Title('Soal Ujian')]

    public $ujian;
    public $santri;
    public $hasilUjian;
    public $currentSoal = 0;
    public $jawaban = [];
    public $waktuMulai;
    public $waktuSelesai;
    public $sisa_waktu;

    #[On('timeUp')]
    public function handleTimeUp()
    {
        $this->selesaiUjian();
    }

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

        $this->ujian = Ujian::findOrFail($ujianId);
        
        // Check if exam is active
        if ($this->ujian->status_ujian !== 'aktif') {
            return redirect()->route('santri.dashboard-ujian')->with('error', 'Ujian tidak tersedia.');
        }

        // Check if exam is already completed
        $existingHasil = HasilUjian::where('santri_id', $this->santri->id)
            ->where('ujian_id', $this->ujian->id)
            ->where('status', 'selesai')
            ->first();

        if ($existingHasil) {
            return redirect()->route('santri.selesai-ujian', ['ujianId' => $ujianId]);
        }

        // Get or create hasil ujian
        $this->hasilUjian = HasilUjian::firstOrCreate(
            [
                'santri_id' => $this->santri->id,
                'ujian_id' => $this->ujian->id,
                'status' => 'sedang_ujian'
            ],
            [
                'waktu_mulai' => now(),
                'waktu_selesai' => null
            ]
        );

        $this->waktuMulai = $this->hasilUjian->waktu_mulai;
        $this->waktuSelesai = $this->waktuMulai->addMinutes($this->ujian->durasi);
        $this->sisa_waktu = now()->diffInSeconds($this->waktuSelesai);

        // Load saved answers if any
        $this->loadJawaban();
    }

    public function nextSoal()
    {
        if ($this->currentSoal < $this->ujian->jumlah_soal - 1) {
            $this->currentSoal++;
        }
    }

    public function prevSoal()
    {
        if ($this->currentSoal > 0) {
            $this->currentSoal--;
        }
    }

    public function saveJawaban($jawaban)
    {
        $this->jawaban[$this->currentSoal] = $jawaban;
        // Save to database
    }

    public function loadJawaban()
    {
        // Load jawaban from database
    }

    public function selesaiUjian()
    {
        // Calculate score
        $totalSkor = 0;
        $nilai = 0;
        
        // Update hasil ujian
        $this->hasilUjian->update([
            'waktu_selesai' => now(),
            'total_skor' => $totalSkor,
            'nilai' => $nilai,
            'status' => 'selesai'
        ]);

        return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
    }

    public function render()
    {
        return view('livewire.santri-p-p-d-b.soal-ujian');
    }
} 
        // Jika jawaban yang dikirim sama dengan jawaban yang sudah ada, berarti user ingin menghapus jawaban
        if (isset($this->jawaban[$this->currentSoal]) && $this->jawaban[$this->currentSoal] === $jawaban) {
            unset($this->jawaban[$this->currentSoal]);
            
            // Hapus jawaban dari database
            JawabanUjian::where([
                'hasil_ujian_id' => $this->hasilUjian->id,
                'soal_id' => $currentSoalObj->id
            ])->delete();
        } else {
            $this->saveJawaban($currentSoalObj->id, $jawaban);
        }

        $this->updateBelumDijawab();
    }

    public function saveJawaban($soalId, $jawaban)
    {
        try {
            JawabanUjian::updateOrCreate(
                [
                    'hasil_ujian_id' => $this->hasilUjian->id,
                    'soal_id' => $soalId,
                ],
                ['jawaban' => $jawaban]
            );

            $this->jawaban[$soalId] = $jawaban;
            $this->belumDijawab = $this->ujian->soals->count() - count($this->jawaban);
            
            session()->flash('success', 'Jawaban berhasil disimpan');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    public function handleAutoSave()
    {
        if (isset($this->jawaban[$this->currentSoal])) {
            $this->saveJawaban($this->ujian->soals[$this->currentSoal]->id, $this->jawaban[$this->currentSoal]);
            $this->lastAutoSave = now();
            $this->dispatch('auto-save');
        }
    }

    public function loadJawaban()
    {
        $jawabanUjians = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get();
        foreach ($jawabanUjians as $jawaban) {
            $soalIndex = $this->ujian->soals->search(function($soal) use ($jawaban) {
                return $soal->id === $jawaban->soal_id;
            });
            if ($soalIndex !== false && !empty(trim($jawaban->jawaban))) {
                $this->jawaban[$soalIndex] = $jawaban->jawaban;
            }
        }
    }

    public function updateBelumDijawab()
    {
        $totalSoal = $this->ujian->soals->count();
        $soalDijawab = 0;

        foreach ($this->ujian->soals as $index => $soal) {
            if (isset($this->jawaban[$index])) {
                if ($soal->tipe_soal === 'essay') {
                    // Untuk soal essay, cek apakah jawabannya tidak kosong
                    if (!empty(trim($this->jawaban[$index]))) {
                        $soalDijawab++;
                    }
                } else {
                    // Untuk soal PG, cukup ada jawabannya
                    $soalDijawab++;
                }
            }
        }

        $this->belumDijawab = $totalSoal - $soalDijawab;
    }

    public function checkUnfinishedQuestions()
    {
        $belumDijawab = $this->ujian->soals->count() - count($this->jawaban);
        
        if ($belumDijawab > 0) {
            session()->flash('warning', "Masih ada {$belumDijawab} soal yang belum dijawab. Yakin ingin menyelesaikan ujian?");
            return;
        }
        
        $this->submitUjian();
    }

    public function submitUjian()
    {
        try {
            // Update waktu selesai
            $this->hasilUjian->update([
                'waktu_selesai' => now(),
                'status' => 'selesai'
            ]);

            // Update status santri
            if ($this->santri->status_santri === 'sedang_ujian') {
                $this->santri->update(['status_santri' => 'menunggu']);
            }

            return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyelesaikan ujian: ' . $e->getMessage());
        }

    }

    public function handleTimeUp()
    {
        $this->submitUjian();
    }

    public function render()
    {
        return view('livewire.santri-ppdb.soal-ujian', [
            'soals' => $this->ujian->soals,
        ]);
    }
} 