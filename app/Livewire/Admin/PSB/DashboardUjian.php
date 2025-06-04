<?php

namespace App\Livewire\Admin\PSB;

use App\Livewire\SantriPPDB\UjianForm;
use App\Models\Ujian;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardUjian extends Component
{
    use WithPagination;

    #[Title('Halaman Dashboard Ujian')]

    public UjianForm $ujianForm;

    public $ujianId;

    public $search = '';

    public $perPage = 10;

    public function mount()
    {
        $this->ujianForm = new UjianForm($this, 'ujianForm');
    }

    #[Computed]
    public function listUjian()
    {
        return Ujian::select('id', 'nama_ujian', 'mata_pelajaran', 'tanggal_ujian', 'waktu_mulai', 'waktu_selesai', 'status_ujian')
            ->where('nama_ujian', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);
    }

    public function create()
    {
        $this->ujianId = null;
        $this->ujianForm->reset();
    }

    public function createUjian()
    {
        try {
            $this->ujianForm->validate([
                'nama_ujian' => 'required|string|max:100',
                'mata_pelajaran' => 'required|string|max:100',
                'periode_id' => 'required|exists:psb_periodes,id',
                'tanggal_ujian' => 'required|date',
                'waktu_mulai' => 'required',
                'waktu_selesai' => 'required|after:waktu_mulai',
                'status_ujian' => 'required|in:draft,aktif,selesai',
            ]);

            Ujian::create($this->ujianForm->all());

            session()->flash('success', 'Ujian baru berhasil dibuat!');

            $this->resetPage();

            return to_route('admin.master-ujian.dashboard');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->ujianId = $id;
        $ujianEdit = Ujian::findOrFail($id);
        $this->ujianForm->fill($ujianEdit->toArray());
    }

    public function updateUjian()
    {
        try {
            $this->ujianForm->validate([
                'nama_ujian' => 'required|string|max:100',
                'mata_pelajaran' => 'required|string|max:100',
                'periode_id' => 'required|exists:psb_periodes,id',
                'tanggal_ujian' => 'required|date',
                'waktu_mulai' => 'required',
                'waktu_selesai' => 'required|after:waktu_mulai',
                'status_ujian' => 'required|in:draft,aktif,selesai',
            ]);

            Ujian::findOrFail($this->ujianId)->update($this->ujianForm->all());

            session()->flash('success', 'Ujian berhasil diupdate!');

            $this->resetPage();

            return to_route('admin.master-ujian.dashboard');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteUjian($id)
    {
        try {
            $ujian = Ujian::findOrFail($id);
            session()->flash('success', 'Berhasil hapus ' . $ujian->nama_ujian);
            $ujian->delete();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.psb.dashboard-ujian');
    }
}