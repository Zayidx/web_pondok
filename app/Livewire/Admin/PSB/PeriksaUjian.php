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
        
        // Initialize all soals with default values
        foreach ($this->soals as $soal) {
            // For PG questions, use their pre-set points, otherwise use 0
            $this->nilaiSoal[$soal->id] = $soal->tipe_soal === 'pg' ? $soal->poin : 0;
        }
        
        // Load existing nilai and komentar if any
        foreach ($this->jawabanUjian as $jawaban) {
            if (isset($jawaban->nilai)) {
                $this->nilaiSoal[$jawaban->soal_id] = $jawaban->nilai;
            }
            $this->komentar[$jawaban->soal_id] = $jawaban->komentar;
        }

        $this->calculateTotalNilai();
    }

    public function updatedNilaiSoal($value, $key)
    {
        $this->calculateTotalNilai();
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

    public function calculateTotalNilai()
    {
        $this->totalNilai = 0;
        $jumlahSoal = 0;
        
        foreach ($this->nilaiSoal as $soalId => $nilai) {
            if (is_numeric($nilai)) {
                $this->totalNilai += $nilai;
                $jumlahSoal++;
            }
        }
        
        $this->rataRata = $jumlahSoal > 0 ? round($this->totalNilai / $jumlahSoal, 2) : 0;
    }
    
    public function simpanNilai()
    {
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            foreach ($this->nilaiSoal as $soalId => $nilai) {
                if (isset($this->jawabanUjian[$soalId])) {
                    $this->jawabanUjian[$soalId]->update([
                        'nilai' => $nilai,
                        'komentar' => $this->komentar[$soalId] ?? null
                    ]);
                }
            }
            
            $this->calculateTotalNilai();
            
            $this->hasilUjian->update([
                'nilai' => $this->rataRata,
                'status' => 'dinilai'
            ]);
            
            DB::commit();
            
            session()->flash('message', 'Nilai berhasil disimpan.');
            return redirect()->route('admin.master-ujian.hasil-ujian');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan nilai.');
        }
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
 
 
 
 
 