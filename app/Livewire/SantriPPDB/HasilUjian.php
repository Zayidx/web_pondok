<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\Ujian;
use App\Models\PSB\HasilUjian as HasilUjianModel;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\JawabanUjian;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\PSB\SoalUjian;
use App\Models\PSB\JawabanSantri;
use App\Models\PSB\Siswa;

class HasilUjian extends Component
{
    #[Layout('components.layouts.ujian')]
    #[Title('Hasil Ujian')]

    public $ujian;
    public $santri;
    public $hasilUjian;
    public $waktuMulai;
    public $waktuSelesai;
    public $durasiPengerjaan;
    public $jumlahSoal;
    public $soalTerjawab;
    public $jumlahSoalPG;
    public $jumlahSoalEssay;
    public $nilaiPG;
    public $nilaiEssay;
    public $maxNilaiPG;
    public $maxNilaiEssay;
    public $jumlahBenarPG;
    public $totalNilai = 0;

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
        $this->hasilUjian = HasilUjianModel::where('santri_id', $this->santri->id)
            ->where('ujian_id', $this->ujian->id)
            ->firstOrFail();

        // Set waktu mulai dan selesai
        $this->waktuMulai = Carbon::parse($this->hasilUjian->waktu_mulai);
        $this->waktuSelesai = Carbon::parse($this->hasilUjian->waktu_selesai);
        $this->durasiPengerjaan = $this->waktuMulai->diffInMinutes($this->waktuSelesai);

        // Get jumlah soal terjawab
        $this->soalTerjawab = $this->hasilUjian->jawabanUjians()->count();

        // Calculate PG and Essay scores
        $soals = $this->ujian->soals;
        $this->jumlahSoalPG = $soals->where('tipe_soal', 'pg')->count();
        $this->jumlahSoalEssay = $soals->where('tipe_soal', 'essay')->count();
        
        $this->maxNilaiPG = $this->jumlahSoalPG * 20; // 20 points per PG
        $this->maxNilaiEssay = $this->jumlahSoalEssay * 40; // 40 points per Essay

        if ($this->hasilUjian->nilai !== null) {
            $jawabanUjians = $this->hasilUjian->jawabanUjians()->with('soal')->get();
            
            // Calculate PG score
            $pgJawaban = $jawabanUjians->filter(function ($jawaban) {
                return $jawaban->soal->tipe_soal === 'pg';
            });
            
            $this->jumlahBenarPG = $pgJawaban->filter(function ($jawaban) {
                return $jawaban->jawaban == $jawaban->soal->kunci_jawaban;
            })->count();
            
            $this->nilaiPG = $this->jumlahBenarPG * 20;

            // Calculate Essay score
            $essayJawaban = $jawabanUjians->filter(function ($jawaban) {
                return $jawaban->soal->tipe_soal === 'essay';
            });
            
            $this->nilaiEssay = $essayJawaban->sum('nilai') * 40;
        }

        $this->calculateTotalNilai();
    }

    public function calculateTotalNilai()
    {
        $soalUjian = SoalUjian::where('ujian_id', $this->ujian->id)->get();
        $jawabanSantri = JawabanSantri::where('ujian_id', $this->ujian->id)
            ->where('santri_id', $this->santri->id)
            ->get()
            ->keyBy('soal_id');

        $this->totalNilai = 0;

        foreach ($soalUjian as $soal) {
            $jawaban = $jawabanSantri->get($soal->id);
            
            if ($soal->jenis_soal === 'PG') {
                // Calculate PG points automatically
                if ($jawaban && $jawaban->jawaban === $soal->kunci_jawaban) {
                    $this->totalNilai += $soal->bobot_poin;
                }
            } else {
                // Add essay points from manual input
                $this->totalNilai += $jawaban ? ($jawaban->poin ?? 0) : 0;
            }
        }
    }

    public function getNilaiGrade($nilai)
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        if ($nilai >= 60) return 'D';
        return 'E';
    }

    public function terimaSantri($siswaId)
    {
        try {
            $siswa = Siswa::findOrFail($siswaId);
            $siswa->update([
                'status' => 'daftar_ulang'
            ]);

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Status santri berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Gagal memperbarui status santri'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.santri-p-p-d-b.hasil-ujian');
    }
} 