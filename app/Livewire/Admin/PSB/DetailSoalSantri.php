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
                $this->poinEssay[$soal->id] = $jawaban->nilai ?? null; // Gunakan null agar input kosong
                $this->komentarEssay[$soal->id] = $jawaban->komentar ?? null;
            }
        }
        $this->calculateTotalPoin();
    }

    public function calculateTotalPoin()
    {
        $this->totalPoin = 0;
        foreach ($this->soalUjian as $soal) {
            $jawaban = $this->jawabanUjian->get($soal->id);
            if ($jawaban) {
                if ($soal->tipe_soal === 'pg') {
                    // Tambahkan poin PG yang sudah dihitung saat santri submit
                    $this->totalPoin += (float)($jawaban->nilai ?? 0);
                } elseif ($soal->tipe_soal === 'essay') {
                    // Jika soal essay, tambahkan poin dari input admin (poinEssay)
                    $this->totalPoin += (float)($this->poinEssay[$soal->id] ?? 0);
                }
            }
        }
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
        $this->validate(); // Validasi semua input

        DB::transaction(function () {
            // Update nilai dan komentar untuk setiap jawaban
            foreach ($this->soalUjian as $soal) {
                $jawaban = $this->jawabanUjian->get($soal->id);
                if ($jawaban) {
                    if ($soal->tipe_soal === 'essay') {
                        $jawaban->update([
                            'nilai' => (float)($this->poinEssay[$soal->id] ?? 0), // Simpan nilai essay dari input admin
                            'komentar' => $this->komentarEssay[$soal->id] ?? null,
                        ]);
                    }
                    // Jawaban PG tidak perlu diupdate nilainya karena sudah dihitung saat santri submit
                }
            }

            // Re-fetch answers to ensure the most up-to-date 'nilai' is used for total calculation
            // Ini penting jika ada data yang diubah di transaksi lain, tapi dalam transaksi ini tidak begitu perlu
            // $this->jawabanUjian = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get()->keyBy('soal_id');
            $this->calculateTotalPoin(); // Hitung ulang total poin setelah update essay

            Log::info('Updating HasilUjian nilai_akhir: ' . $this->totalPoin);
            $this->hasilUjian->update([
                'nilai_akhir' => $this->totalPoin, // Update nilai_akhir di HasilUjian dengan total poin keseluruhan
                'status' => 'selesai', // Ubah status menjadi 'selesai' setelah dinilai
            ]);

            // Mengambil semua hasil ujian santri yang sudah selesai untuk menghitung total keseluruhan
            $semuaHasilUjianSelesai = HasilUjian::where('santri_id', $this->santriId)
                                          ->where('status', 'selesai')
                                          ->get();

            // Menghitung rata-rata baru dari semua ujian yang selesai
            $rataRataBaru = $semuaHasilUjianSelesai->avg('nilai_akhir') ?? 0;
            // Menghitung total nilai keseluruhan dari semua ujian yang selesai
            $totalNilaiKeseluruhan = $semuaHasilUjianSelesai->sum('nilai_akhir');

            Log::info('Updating Santri rata_rata_ujian: ' . $rataRataBaru . ' and total_nilai_semua_ujian: ' . $totalNilaiKeseluruhan);
            // Memperbarui kolom di model PendaftaranSantri
            $this->santri->update([
                'rata_rata_ujian'         => $rataRataBaru,
                'total_nilai_semua_ujian' => $totalNilaiKeseluruhan,
            ]);

            Log::info('Transaction committed successfully.');
        });

        session()->flash('success', 'Semua nilai berhasil disimpan!');
        $this->dispatch('nilai-tersimpan');
        // Redirect ke halaman daftar hasil ujian santri (opsional)
        return redirect()->route('admin.psb.ujian.hasil'); // Contoh redirect
    }

    public function render()
    {
        return view('livewire.admin.psb.detail-soal-santri');
    }
}