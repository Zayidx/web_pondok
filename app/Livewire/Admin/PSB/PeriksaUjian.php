<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\Ujian;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\Soal;
use Illuminate\Support\Facades\DB;

class PeriksaUjian extends Component
{
    public $santri;
    public $ujian;
    public $hasilUjian;
    public $jawabanUjian;
    public $soals;
    public $nilaiSoal = [];
    public $komentar = [];
    public $selectedSoal = null;
    public $totalNilai = 0;
    public $rataRata = 0;
    
    protected $rules = [
        'nilaiSoal.*' => 'required|numeric|min:0|max:100',
        'komentar.*' => 'nullable|string'
    ];

    protected $messages = [
        'nilaiSoal.*.required' => 'Nilai harus diisi',
        'nilaiSoal.*.numeric' => 'Nilai harus berupa angka',
        'nilaiSoal.*.min' => 'Nilai minimal 0',
        'nilaiSoal.*.max' => 'Nilai maksimal 100'
    ];
    
    public function mount($santriId, $ujianId)
    {
        $this->santri = PendaftaranSantri::findOrFail($santriId);
        $this->ujian = Ujian::with('soals')->findOrFail($ujianId);
        $this->hasilUjian = HasilUjian::where([
            'santri_id' => $santriId,
            'ujian_id' => $ujianId
        ])->firstOrFail();
        
        $this->jawabanUjian = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)
            ->get()
            ->keyBy('soal_id');
            
        $this->soals = $this->ujian->soals;
        
        // Load existing scores and comments
        foreach ($this->soals as $soal) {
            $jawaban = $this->jawabanUjian->get($soal->id);
            if ($jawaban) {
                $this->nilaiSoal[$soal->id] = $jawaban->nilai;
                $this->komentar[$soal->id] = $jawaban->komentar;
            }
        }
        
        $this->hitungTotalNilai();
    }

    public function updatedNilaiSoal($value, $key)
    {
        $this->hitungTotalNilai();
    }

    public function getPilihanJawaban($soal)
    {
        if (!$soal->pilihan_jawaban) {
            return [];
        }

        try {
            $pilihan = json_decode($soal->pilihan_jawaban, true);
            return is_array($pilihan) ? $pilihan : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function showEssayModal($soalId)
    {
        $this->selectedSoal = $this->soals->firstWhere('id', $soalId);
        $this->dispatch('showEssayModal');
    }

    public function simpanNilaiEssay()
    {
        $this->validate([
            'nilaiSoal.' . $this->selectedSoal->id => 'required|numeric|min:0|max:100'
        ]);

        $jawaban = $this->jawabanUjian->get($this->selectedSoal->id);
        if ($jawaban) {
            $jawaban->update([
                'nilai' => $this->nilaiSoal[$this->selectedSoal->id],
                'komentar' => $this->komentar[$this->selectedSoal->id] ?? null
            ]);
        }

        $this->hitungTotalNilai();
        $this->dispatch('hideEssayModal');
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Nilai essay berhasil disimpan'
        ]);
    }

    public function simpanNilai()
    {
        $this->validate([
            'nilaiSoal.*' => 'required|numeric|min:0|max:100'
        ]);

        foreach ($this->nilaiSoal as $soalId => $nilai) {
            $jawaban = $this->jawabanUjian->get($soalId);
            if ($jawaban) {
                $jawaban->update([
                    'nilai' => $nilai,
                    'komentar' => $this->komentar[$soalId] ?? null
                ]);
            }
        }

        $this->hasilUjian->update([
            'status' => 'dinilai',
            'total_nilai' => $this->totalNilai,
            'rata_rata' => $this->rataRata
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Semua nilai berhasil disimpan'
        ]);

        return redirect()->route('admin.master-ujian.hasil-ujian');
    }

    protected function hitungTotalNilai()
    {
        $totalNilai = 0;
        $jumlahSoal = 0;

        foreach ($this->nilaiSoal as $nilai) {
            if ($nilai !== null) {
                $totalNilai += $nilai;
                $jumlahSoal++;
            }
        }

        $this->totalNilai = $totalNilai;
        $this->rataRata = $jumlahSoal > 0 ? round($totalNilai / $jumlahSoal, 2) : 0;
    }

    public function getJawabanPG($soal, $jawaban)
    {
        if ($soal->tipe_soal !== 'pg' || !is_numeric($jawaban)) {
            return $jawaban;
        }

        // Convert numeric answer (0-4) to A-E
        return chr(65 + $jawaban);
    }
    
    public function render()
    {
        return view('livewire.admin.psb.periksa-ujian');
    }
} 
 
 
 
 
 