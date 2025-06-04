<?php

namespace App\Livewire\Admin\PSB;

use App\Livewire\SantriPPDB\SoalForm;
use App\Models\PSB\Soal;
use App\Models\Ujian;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class DetailUjian extends Component
{
    use WithPagination;
    #[Title('Halaman Soal Ujian')]

    public SoalForm $soalForm;
    public $soalId;
    public $ujianId;
    public $ujian;

    public function mount($ujianId)
    {
        $this->ujianId = $ujianId;
        $this->ujian = Ujian::findOrFail($ujianId);
        $this->soalForm = new SoalForm($this, 'soalForm');
        $this->soalForm->ujian_id = $this->ujianId;
    }

    #[Computed]
    public function listSoal()
    {
        return Soal::where('ujian_id', $this->ujianId)->paginate(10);
    }

    public function create()
    {
        $this->soalId = null;
        $this->soalForm = new SoalForm($this, 'soalForm');
        $this->soalForm->ujian_id = $this->ujianId;
        $this->soalForm->tipe_soal = 'pg';
    }

    public function addOption()
    {
        $this->soalForm->addOption();
    }

    public function removeOption($index)
    {
        $this->soalForm->removeOption($index);
    }

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

        return $correctIndex;
    }

    public function createSoal()
    {
        try {
            $data = [
                'ujian_id' => $this->ujianId,
                'tipe_soal' => $this->soalForm->tipe_soal,
                'pertanyaan' => $this->soalForm->pertanyaan,
            ];

            if ($this->soalForm->tipe_soal === 'pg') {
                $data['opsi'] = $this->soalForm->opsi;
                $data['kunci_jawaban'] = $this->findCorrectAnswerIndex($this->soalForm->opsi);
            }

            Soal::create($data);
            $this->dispatch('close-modal', id: 'createOrUpdateSoal');
            session()->flash('success', 'Soal baru berhasil dibuat!');
            $this->create(); // Reset form
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->soalId = $id;
        $soal = Soal::findOrFail($id);
        $this->soalForm = new SoalForm($this, 'soalForm');
        $this->soalForm->fill([
            'ujian_id' => $soal->ujian_id,
            'tipe_soal' => $soal->tipe_soal,
            'pertanyaan' => $soal->pertanyaan,
            'opsi' => $soal->opsi,
        ]);
    }

    public function updateSoal()
    {
        try {
            $data = [
                'tipe_soal' => $this->soalForm->tipe_soal,
                'pertanyaan' => $this->soalForm->pertanyaan,
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

    public function render()
    {
        return view('livewire.admin.psb.detail-ujian');
    }
}