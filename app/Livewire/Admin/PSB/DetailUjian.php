<?php

namespace App\Livewire\Admin\PSB;

use App\Livewire\SantriPPDB\SoalForm;
use App\Models\PSB\Soal;
use App\Models\PSB\Ujian;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * DetailUjian Component
 * * Komponen ini menangani manajemen soal-soal dalam sebuah ujian, termasuk:
 * - Menampilkan daftar soal
 * - Menambah soal baru (PG atau Essay)
 * - Mengubah soal yang sudah ada
 * - Menghapus soal
 */
class DetailUjian extends Component
{
    use WithPagination; // Trait untuk fitur pagination di Livewire

    #[Title('Halaman Soal Ujian')] // Judul halaman yang akan ditampilkan di browser

    // Properties untuk form dan data
    public SoalForm $soalForm;    // Form untuk input/edit soal
    public $soalId;               // ID soal yang sedang diedit (null jika membuat baru)
    public $ujianId;              // ID ujian yang sedang dikelola
    public $ujian;                // Data ujian yang sedang dikelola

    /**
     * Inisialisasi komponen saat pertama kali dimuat
     * * @param int $ujianId ID ujian yang akan dikelola soal-soalnya
     */
    public function mount($ujianId)
    {
        $this->ujianId = $ujianId;
        $this->ujian = Ujian::findOrFail($ujianId);
        $this->soalForm = new SoalForm($this, 'soalForm');
        $this->soalForm->ujian_id = $this->ujianId;
    }

    /**
     * Mengambil daftar soal untuk ujian yang sedang aktif
     * dengan pagination 10 item per halaman
     * * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function listSoal()
    {
        return Soal::where('ujian_id', $this->ujianId)
            ->orderByRaw("CASE WHEN tipe_soal = 'pg' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'asc')
            ->paginate(10);
    }

    /**
     * Menyiapkan form untuk membuat soal baru
     */
    public function create()
    {
        $this->soalId = null;
        $this->soalForm = new SoalForm($this, 'soalForm');
        $this->soalForm->ujian_id = $this->ujianId;
        $this->soalForm->tipe_soal = 'pg';
    }

    /**
     * Menambah opsi baru untuk soal pilihan ganda
     */
    public function addOption()
    {
        $this->soalForm->addOption();
    }

    /**
     * Menghapus opsi dari soal pilihan ganda
     * * @param int $index Index opsi yang akan dihapus
     */
    public function removeOption($index)
    {
        $this->soalForm->removeOption($index);
    }

    /**
     * Mencari index jawaban yang benar berdasarkan bobot nilai tertinggi
     * * @param array $options Array opsi jawaban dengan bobot nilainya
     * @return int Index dari opsi dengan bobot tertinggi
     */
    protected function findCorrectAnswerIndex($options)
    {
        $maxBobot = -1;
        $correctIndex = 0;

        foreach ($options as $index => $option) {
            $bobot = (int) $option['bobot'];
            if ($bobot > $maxBobot) {
                $maxBobot = $bobot;
                $correctIndex = $index;
            }
        }

        return chr(65 + $correctIndex); // Mengembalikan huruf (A, B, C, D)
    }

    /**
     * Menyimpan soal baru ke database
     * * Untuk soal PG:
     * - Menyimpan opsi jawaban
     * - Menentukan kunci jawaban berdasarkan bobot tertinggi
     * * Untuk Essay:
     * - Hanya menyimpan pertanyaan
     */
    public function createSoal()
    {
        try {
            $data = [
                'ujian_id' => $this->ujianId,
                'tipe_soal' => $this->soalForm->tipe_soal,
                'pertanyaan' => $this->soalForm->pertanyaan,
                'poin' => 1
            ];

            if ($this->soalForm->tipe_soal === 'pg') {
                $data['opsi'] = $this->soalForm->opsi;
                $data['kunci_jawaban'] = $this->findCorrectAnswerIndex($this->soalForm->opsi);
            } else {
                $data['opsi'] = null;
                $data['kunci_jawaban'] = null;
            }

            Soal::create($data);
            $this->dispatch('close-modal', id: 'createOrUpdateSoal');
            session()->flash('success', 'Soal baru berhasil dibuat!');
            $this->create(); // Reset form
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menyiapkan form untuk mengedit soal yang sudah ada
     * * @param int $id ID soal yang akan diedit
     */
    public function edit($id)
    {
        $this->soalId = $id;
        $soal = Soal::findOrFail($id);
        $this->soalForm = new SoalForm($this, 'soalForm');
        $this->soalForm->fill([
            'ujian_id' => $soal->ujian_id,
            'tipe_soal' => $soal->tipe_soal,
            'pertanyaan' => $soal->pertanyaan,
            'opsi' => $soal->opsi ?? []
        ]);
    }

    /**
     * Mengupdate soal yang sudah ada di database
     * * Untuk soal PG:
     * - Update opsi jawaban
     * - Update kunci jawaban berdasarkan bobot tertinggi
     * * Untuk Essay:
     * - Hanya update pertanyaan
     * - Hapus opsi dan kunci jawaban
     */
    public function updateSoal()
    {
        try {
            $data = [
                'tipe_soal' => $this->soalForm->tipe_soal,
                'pertanyaan' => $this->soalForm->pertanyaan
            ];

            if ($this->soalForm->tipe_soal === 'pg') {
                $data['opsi'] = $this->soalForm->opsi;
                $data['kunci_jawaban'] = $this->findCorrectAnswerIndex($this->soalForm->opsi);
            } else {
                $data['opsi'] = null;
                $data['kunci_jawaban'] = null;
            }

            Soal::findOrFail($this->soalId)->update($data);
            $this->dispatch('close-modal', id: 'createOrUpdateSoal');
            session()->flash('success', 'Soal berhasil diupdate!');
            $this->create(); // Reset form
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus soal dari database
     * * @param int $id ID soal yang akan dihapus
     */
    public function deleteSoal($id)
    {
        try {
            $soal = Soal::findOrFail($id);
            $soal->delete();
            session()->flash('success', 'Berhasil hapus soal');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menyiapkan data untuk preview ujian dari perspektif santri
     */
    public function previewUjian()
    {
        // Method ini kosong karena preview hanya membutuhkan data yang sudah ada
        // Data ujian dan soal-soal sudah tersedia melalui properti $ujian dan method listSoal()
    }

    /**
     * Render view untuk komponen ini
     * * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.psb.detail-ujian');
    }
}