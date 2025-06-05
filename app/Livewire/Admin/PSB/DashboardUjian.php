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

    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $sortMataPelajaran = '';
    public $filterTanggal = '';
    public $sortStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortMataPelajaran' => ['except' => ''],
        'filterTanggal' => ['except' => ''],
        'sortStatus' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function mount()
    {
        $this->ujianForm = new UjianForm($this, 'ujianForm');
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'sortMataPelajaran',
            'filterTanggal',
            'sortStatus',
            'sortField',
            'sortDirection'
        ]);
    }

    #[Computed]
    public function listUjian()
    {
        return Ujian::select('id', 'nama_ujian', 'mata_pelajaran', 'tanggal_ujian', 'waktu_mulai', 'waktu_selesai', 'status_ujian')
            ->when($this->search, function ($query) {
                $query->where('nama_ujian', 'like', '%' . $this->search . '%');
            })
            ->when($this->sortMataPelajaran, function ($query) {
                $query->orderBy('mata_pelajaran', $this->sortMataPelajaran);
            })
            ->when($this->filterTanggal, function ($query) {
                $query->whereDate('tanggal_ujian', $this->filterTanggal);
            })
            ->when($this->sortStatus, function ($query) {
                $query->where('status_ujian', $this->sortStatus);
            })
            ->orderBy($this->sortField, $this->sortDirection)
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