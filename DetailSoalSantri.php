<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\JawabanSantri;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class DetailSoalSantri extends Component
{
    public $ujian;
    public $soal;
    public $jawaban;
    public $nilai = [];
    public $totalNilai = 0;
    public $nilaiAkhir = 0;
    public $isLoading = false;

    protected $rules = [
        'nilai.*' => 'nullable|numeric|min:0'
    ];

    public function mount($id)
    {
        try {
            $this->ujian = Ujian::with(['soal', 'jawabanSantri'])
                ->findOrFail($id);
            
            $this->soal = $this->ujian->soal;
            $this->jawaban = $this->ujian->jawabanSantri;
            
            // Initialize nilai array with existing values
            foreach ($this->soal as $index => $s) {
                $jawaban = $this->jawaban->where('soal_id', $s->id)->first();
                if ($s->jenis_soal === 'pilihan_ganda') {
                    // Untuk pilihan ganda, nilai otomatis berdasarkan jawaban benar/salah
                    $this->nilai[$index] = ($jawaban && $jawaban->jawaban === $s->jawaban_benar) ? $s->nilai : 0;
                } else {
                    // Untuk essay, ambil nilai yang sudah ada
                    $this->nilai[$index] = $jawaban ? $jawaban->nilai : 0;
                }
            }
            
            $this->calculateTotalNilai();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    #[Computed]
    public function getTotalNilaiProperty()
    {
        return array_sum($this->nilai);
    }

    #[Computed]
    public function getNilaiAkhirProperty()
    {
        $totalNilaiMaksimal = $this->soal->sum('nilai');
        if ($totalNilaiMaksimal > 0) {
            return ($this->getTotalNilaiProperty() / $totalNilaiMaksimal) * 100;
        }
        return 0;
    }

    public function updatedNilai($value, $key)
    {
        try {
            // Hanya validasi untuk soal essay
            if ($this->soal[$key]->jenis_soal === 'essay') {
                $this->validateOnly("nilai.$key");
                $this->calculateTotalNilai();
            }
        } catch (\Exception $e) {
            $this->addError("nilai.$key", 'Nilai harus berupa angka positif');
        }
    }

    protected function calculateTotalNilai()
    {
        try {
            $this->totalNilai = array_sum($this->nilai);
            $totalNilaiMaksimal = $this->soal->sum('nilai');
            if ($totalNilaiMaksimal > 0) {
                $this->nilaiAkhir = ($this->totalNilai / $totalNilaiMaksimal) * 100;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghitung nilai: ' . $e->getMessage());
        }
    }

    public function simpanNilai()
    {
        $this->isLoading = true;
        
        try {
            $this->validate();
            
            DB::beginTransaction();
            
            foreach ($this->soal as $index => $s) {
                $jawaban = JawabanSantri::where('ujian_id', $this->ujian->id)
                    ->where('soal_id', $s->id)
                    ->first();
                
                if ($jawaban) {
                    // Hanya update nilai untuk soal essay
                    if ($s->jenis_soal === 'essay') {
                        $jawaban->update([
                            'nilai' => $this->nilai[$index] ?? 0
                        ]);
                    }
                }
            }
            
            $this->ujian->update([
                'total_nilai' => $this->totalNilai,
                'nilai_akhir' => $this->nilaiAkhir
            ]);
            
            DB::commit();
            session()->flash('success', 'Nilai berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan nilai: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.detail-soal-santri');
    }
} 