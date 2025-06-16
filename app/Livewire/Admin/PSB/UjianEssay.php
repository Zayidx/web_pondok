<?php

namespace App\Livewire\Admin\PSB;

use App\Models\JawabanSantri;
use App\Models\Ujian;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class UjianEssay extends Component
{
    use WithPagination;
    #[Title('Halaman Penilaian Essay')]

    public $ujianId;
    public $santriId;
    public $jawabanId;
    public $skor;
    public $catatan;

    public function mount($ujianId)
    {
        $this->ujianId = $ujianId;
    }

    #[Computed]
    public function listJawaban()
    {
        return JawabanSantri::where('ujian_id', $this->ujianId)
            ->whereHas('soal', function ($query) {
                $query->where('tipe_soal', 'essay');
            })
            ->with(['santri' => function ($query) {
                $query->select('id', 'nama_lengkap');
            }, 'soal'])
            ->paginate(10);
    }

    public function edit($id)
    {
        $this->jawabanId = $id;
        $jawaban = JawabanSantri::findOrFail($id);
        $this->santriId = $jawaban->santri_id;
        $this->skor = $jawaban->skor;
        $this->catatan = $jawaban->catatan;
    }

    public function updateJawaban()
    {
        try {
            $this->validate([
                'skor' => 'required|integer|min:0',
                'catatan' => 'nullable|string',
            ]);

            $jawaban = JawabanSantri::findOrFail($this->jawabanId);
            $jawaban->update([
                'skor' => $this->skor,
                'catatan' => $this->catatan,
            ]);

            // Update total skor di hasil_ujians
            $totalSkor = JawabanSantri::where('santri_id', $this->santriId)
                ->where('ujian_id', $this->ujianId)
                ->sum('skor');
            \App\Models\HasilUjian::updateOrCreate(
                ['santri_id' => $this->santriId, 'ujian_id' => $this->ujianId],
                ['total_skor' => $totalSkor, 'status' => 'dinilai']
            );

            session()->flash('success', 'Penilaian berhasil disimpan!');

            return redirect()->route('admin.master-ujian.essay', $this->ujianId);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.psb.ujian-essay');
    }
}