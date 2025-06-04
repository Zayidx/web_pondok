<?php

namespace App\Livewire\Admin\PSB;

use App\Livewire\Forms\PeriodeForm;
use App\Models\PSB\Periode;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class PeriodeManager extends Component
{
    use WithPagination;

    #[Title('Kelola Periode Ujian')]

    public PeriodeForm $periodeForm;

    public $periodeId;

    public $search = '';

    public $perPage = 10;

    #[Computed]
    public function listPeriods()
    {
        return Periode::select('id', 'nama_periode', 'periode_mulai', 'periode_selesai', 'status_periode', 'tahun_ajaran')
            ->where('nama_periode', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);
    }

    public function create()
    {
        $this->periodeId = null;
        $this->periodeForm->reset();
    }

    public function createPeriode()
    {
        try {
            $this->periodeForm->validate([
                'nama_periode' => 'required|string|max:100',
                'periode_mulai' => 'required|date',
                'periode_selesai' => 'required|date|after:periode_mulai',
                'status_periode' => 'required|in:active,inactive',
                'tahun_ajaran' => 'required|string|max:10',
            ]);

            Periode::create($this->periodeForm->all());

            session()->flash('success', 'Periode baru berhasil dibuat!');

            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->periodeId = $id;
        $periodeEdit = Periode::findOrFail($id);
        $this->periodeForm->fill($periodeEdit->toArray());
    }

    public function updatePeriode()
    {
        try {
            $this->periodeForm->validate([
                'nama_periode' => 'required|string|max:100',
                'periode_mulai' => 'required|date',
                'periode_selesai' => 'required|date|after:periode_mulai',
                'status_periode' => 'required|in:active,inactive',
                'tahun_ajaran' => 'required|string|max:10',
            ]);

            Periode::findOrFail($this->periodeId)->update($this->periodeForm->all());

            session()->flash('success', 'Periode berhasil diupdate!');

            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deletePeriode($id)
    {
        try {
            $periode = Periode::findOrFail($id);
            if ($periode->ujians()->exists() || $periode->pendaftaranSantris()->exists()) {
                throw new \Exception('Periode tidak dapat dihapus karena masih digunakan.');
            }
            session()->flash('success', 'Berhasil hapus ' . $periode->nama_periode);
            $periode->delete();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.psb.periode-manager');
    }
}