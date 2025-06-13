<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\Soal;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\Ujian;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

#[Title('Detail & Penilaian Ujian Santri')]
class DetailSoalSantri extends Component
{
    public $ujianId;
    public $santriId;
    public $ujian;
    public $santri;
    public $soalUjian;
    public Collection $jawabanUjian;
    public $hasilUjian;
    public $poinEssay = []; // Array untuk menyimpan poin essay yang diinput admin
    public $komentarEssay = []; // Array untuk menyimpan komentar essay
    public $totalPoin = 0; // Total poin untuk ujian ini

    // Hilangkan aturan validasi max:100 untuk poinEssay
    protected $rules = [
        'poinEssay.*' => 'nullable|numeric|min:0', // Tanpa batasan maksimal 100
        'komentarEssay.*' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'poinEssay.*.numeric' => 'Nilai harus berupa angka.',
        'poinEssay.*.min' => 'Nilai minimal 0.',
        'komentarEssay.*.max' => 'Komentar terlalu panjang (maks. 500 karakter).',
    ];

    public function mount($ujianId, $santriId)
    {
        $this->ujianId = $ujianId;
        $this->santriId = $santriId;
        $this->santri = PendaftaranSantri::findOrFail($santriId);
        $this->ujian = Ujian::findOrFail($ujianId);
        $this->loadData();
        $this->calculateTotalPoin(); // Calculate initial total
    }

    public function loadData()
    {
        $this->soalUjian = Soal::where('ujian_id', $this->ujianId)->orderBy('id', 'asc')->get();
        $this->hasilUjian = HasilUjian::where('santri_id', $this->santriId)
                                      ->where('ujian_id', $this->ujianId)
                                      ->firstOrFail();

        // Mengambil semua jawaban ujian santri yang terkait
        $this->jawabanUjian = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get()->keyBy('soal_id');

        // Inisialisasi poinEssay dan komentarEssay dari data yang sudah ada
        foreach ($this->soalUjian as $soal) {
            $jawaban = $this->jawabanUjian->get($soal->id);
            if ($soal->tipe_soal === 'essay') {
                $this->poinEssay[$soal->id] = $jawaban->nilai ?? null;
                $this->komentarEssay[$soal->id] = $jawaban->komentar ?? null;
            } elseif ($soal->tipe_soal === 'pg' && $jawaban) {
                // Update nilai PG jika ada
                if ($jawaban->jawaban) {
                    $answerIndex = ord(strtoupper($jawaban->jawaban)) - 65;
                    if (isset($soal->opsi[$answerIndex]['bobot'])) {
                        $jawaban->update(['nilai' => (float)$soal->opsi[$answerIndex]['bobot']]);
                    }
                }
            }
        }
    }

    public function calculateTotalPoin()
    {
        $totalPG = 0;
        $totalEssay = 0;

        foreach ($this->soalUjian as $soal) {
            $jawaban = $this->jawabanUjian->get($soal->id);
            if ($jawaban) {
                if ($soal->tipe_soal === 'pg') {
                    if ($jawaban->jawaban) {
                        $answerIndex = (int)$jawaban->jawaban;
                        if (isset($soal->opsi[$answerIndex]['bobot'])) {
                            $poinPG = (float)$soal->opsi[$answerIndex]['bobot'];
                            $totalPG += $poinPG;
                            $jawaban->update(['nilai' => $poinPG]);
                        }
                    }
                } elseif ($soal->tipe_soal === 'essay') {
                    $poinEssay = (float)($this->poinEssay[$soal->id] ?? 0);
                    $totalEssay += $poinEssay;
                    $jawaban->update(['nilai' => $poinEssay]);
                }
            }
        }

        $this->totalPoin = $totalPG + $totalEssay;

        // Update nilai_akhir in hasil_ujian
        $this->hasilUjian->update([
            'nilai_akhir' => $this->totalPoin,
            'status' => 'selesai'
        ]);

        // Update santri's scores
        $semuaHasilUjianSelesai = HasilUjian::where('santri_id', $this->santriId)
            ->where('status', 'selesai')
            ->get();

        $rataRataBaru = $semuaHasilUjianSelesai->avg('nilai_akhir') ?? 0;
        $totalNilaiKeseluruhan = $semuaHasilUjianSelesai->sum('nilai_akhir');

        $this->santri->update([
            'rata_rata_ujian' => $rataRataBaru,
            'total_nilai_semua_ujian' => $totalNilaiKeseluruhan
        ]);

        Log::info('Score calculation completed', [
            'totalPG' => $totalPG,
            'totalEssay' => $totalEssay,
            'totalPoin' => $this->totalPoin,
            'rataRata' => $rataRataBaru,
            'totalNilai' => $totalNilaiKeseluruhan
        ]);
    }

    // Metode ini dipanggil saat input poin essay diubah
    public function updatedPoinEssay($value, $soalId)
    {
        $this->validateOnly("poinEssay.{$soalId}");
        $this->poinEssay[$soalId] = (float)$value; // Jangan ada batasan max:100 di sini
        $this->calculateTotalPoin(); // Hitung ulang total poin setelah perubahan
    }

    // Metode ini dipanggil saat input komentar essay diubah
    public function updatedKomentarEssay($value, $soalId)
    {
        $this->validateOnly("komentarEssay.{$soalId}");
        $this->komentarEssay[$soalId] = $value;
    }

    public function saveNilai()
    {
        $this->validate();

        DB::transaction(function () {
            // Update nilai dan komentar untuk setiap jawaban
            foreach ($this->soalUjian as $soal) {
                $jawaban = $this->jawabanUjian->get($soal->id);
                if ($jawaban) {
                    if ($soal->tipe_soal === 'essay') {
                        $jawaban->update([
                            'nilai' => (float)($this->poinEssay[$soal->id] ?? 0),
                            'komentar' => $this->komentarEssay[$soal->id] ?? null,
                        ]);
                    } elseif ($soal->tipe_soal === 'pg') {
                        // Update nilai for PG based on the selected option's bobot
                        $jawabanHuruf = $jawaban->jawaban;
                        if ($jawabanHuruf) {
                            $answerIndex = ord(strtoupper($jawabanHuruf)) - 65;
                            if (isset($soal->opsi[$answerIndex]['bobot'])) {
                                $nilai = (float)$soal->opsi[$answerIndex]['bobot'];
                                $jawaban->update(['nilai' => $nilai]);
                            }
                        }
                    }
                }
            }

            $this->calculateTotalPoin(); // Recalculate total after updates

            Log::info('Updating HasilUjian nilai_akhir: ' . $this->totalPoin);
            $this->hasilUjian->update([
                'nilai_akhir' => $this->totalPoin,
                'status' => 'selesai',
            ]);

            // Calculate and update total score for all exams
            $semuaHasilUjianSelesai = HasilUjian::where('santri_id', $this->santriId)
                                          ->where('status', 'selesai')
                                          ->get();

            // Calculate new average from all completed exams
            $rataRataBaru = $semuaHasilUjianSelesai->avg('nilai_akhir') ?? 0;
            // Calculate total score from all completed exams
            $totalNilaiKeseluruhan = $semuaHasilUjianSelesai->sum('nilai_akhir');

            Log::info('Updating Santri rata_rata_ujian: ' . $rataRataBaru . ' and total_nilai_semua_ujian: ' . $totalNilaiKeseluruhan);
            // Update PendaftaranSantri model columns
            $this->santri->update([
                'rata_rata_ujian' => $rataRataBaru,
                'total_nilai_semua_ujian' => $totalNilaiKeseluruhan,
            ]);

            Log::info('Transaction committed successfully.');
        });

        session()->flash('success', 'Semua nilai berhasil disimpan!');
        $this->dispatch('nilai-tersimpan');
    }

    public function render()
    {
        $this->calculateTotalPoin(); // Recalculate before rendering
        return view('livewire.admin.psb.detail-soal-santri');
    }
}