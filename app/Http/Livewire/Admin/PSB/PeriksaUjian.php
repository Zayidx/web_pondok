<?php

namespace App\Http\Livewire\Admin\PSB;

use App\Models\HasilUjian;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\Santri;
use App\Models\PSB\Soal;
use App\Models\PSB\Ujian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PeriksaUjian extends Component
{
    public $ujianId;
    public $santriId;
    public $ujian;
    public $santri;
    public $hasilUjian;
    public $soals;
    public $jawabanUjian;
    public $nilaiSoal = [];
    public $komentar = [];

    protected $rules = [
        'nilaiSoal.*' => 'required|numeric|min:0|max:100',
        'komentar.*' => 'nullable|string',
    ];

    protected $messages = [
        'nilaiSoal.*.required' => 'Nilai harus diisi',
        'nilaiSoal.*.numeric' => 'Nilai harus berupa angka',
        'nilaiSoal.*.min' => 'Nilai minimal 0',
        'nilaiSoal.*.max' => 'Nilai maksimal 100',
    ];

    public function mount($ujianId, $santriId)
    {
        $this->ujianId = $ujianId;
        $this->santriId = $santriId;
        $this->loadData();
    }

    public function loadData()
    {
        try {
            $this->ujian = Ujian::findOrFail($this->ujianId);
            $this->santri = Santri::findOrFail($this->santriId);
            $this->hasilUjian = HasilUjian::where('ujian_id', $this->ujianId)
                ->where('santri_id', $this->santriId)
                ->firstOrFail();
            
            $this->soals = Soal::where('ujian_id', $this->ujianId)->get();
            
            // Get existing jawaban
            $jawabanUjians = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get();
            $this->jawabanUjian = $jawabanUjians->keyBy('soal_id');

            // Load existing nilai and komentar
            foreach ($jawabanUjians as $jawaban) {
                $this->nilaiSoal[$jawaban->soal_id] = $jawaban->nilai;
                $this->komentar[$jawaban->soal_id] = $jawaban->komentar;
            }
        } catch (\Exception $e) {
            Log::error('Error loading data: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    public function simpanNilai()
    {
        try {
            $this->validate();

            DB::beginTransaction();

            $totalNilai = 0;
            $jumlahSoal = count($this->soals);

            foreach ($this->soals as $soal) {
                $jawaban = $this->jawabanUjian[$soal->id] ?? null;
                
                if (!$jawaban) {
                    $jawaban = new JawabanUjian();
                    $jawaban->hasil_ujian_id = $this->hasilUjian->id;
                    $jawaban->soal_id = $soal->id;
                    $jawaban->jawaban = ''; // Add empty jawaban if not exists
                }

                $jawaban->nilai = $this->nilaiSoal[$soal->id] ?? 0;
                $jawaban->komentar = $this->komentar[$soal->id] ?? '';
                $jawaban->save();

                $totalNilai += $jawaban->nilai;
            }

            // Update nilai rata-rata di hasil ujian
            $nilaiRata = $jumlahSoal > 0 ? $totalNilai / $jumlahSoal : 0;
            $this->hasilUjian->nilai_akhir = $nilaiRata;
            $this->hasilUjian->status = 'selesai_diperiksa';
            $this->hasilUjian->save();

            DB::commit();

            session()->flash('message', 'Nilai berhasil disimpan.');
            return redirect()->route('admin.master-ujian.hasil-ujian');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving nilai: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menyimpan nilai: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.psb.periksa-ujian');
    }
} 
 
 
 
 
 