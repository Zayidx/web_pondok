<?php

namespace App\Livewire\Admin\PSB;

use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\Ujian;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class HasilUjianSantri extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    #[Title('Hasil Ujian Santri')]

    public $perPage = 10;
    public $search = '';
    public $searchAlamat = '';
    public $filters = [
        'status' => '',
        'tipe_pendaftaran' => ''
    ];
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedSantri = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'searchAlamat' => ['except' => ''],
        'filters' => ['except' => ['status' => '', 'tipe_pendaftaran' => '']],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function mount()
    {
        // Initialize with default filter for "sedang_ujian" status
        $this->filters['status'] = 'sedang_ujian';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchAlamat()
    {
        $this->resetPage();
    }

    public function updatingFilters()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->searchAlamat = '';
        $this->filters = [
            'status' => 'sedang_ujian',
            'tipe_pendaftaran' => ''
        ];
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }

    public function getSantriList()
    {
        return PendaftaranSantri::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->searchAlamat, function ($query) {
                $query->where('alamat', 'like', '%' . $this->searchAlamat . '%');
            })
            ->when($this->filters['status'], function ($query) {
                $query->where('status_santri', $this->filters['status']);
            })
            ->when($this->filters['tipe_pendaftaran'], function ($query) {
                $query->where('tipe_pendaftaran', $this->filters['tipe_pendaftaran']);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function terimaSantri($id)
    {
        $santri = PendaftaranSantri::findOrFail($id);
        $santri->update([
            'status_santri' => 'diterima'
        ]);
        session()->flash('success', 'Santri berhasil diterima dan akan melakukan pendaftaran ulang');
    }

    public function tolakSantri($id)
    {
        $santri = PendaftaranSantri::findOrFail($id);
        $santri->update([
            'status_santri' => 'ditolak'
        ]);
        session()->flash('success', 'Santri berhasil ditolak');
    }

    public function getTipeOptions()
    {
        return [
            '' => 'Semua Tipe',
            'reguler' => 'Reguler',
            'olimpiade' => 'Olimpiade',
            'internasional' => 'Internasional'
        ];
    }

    public function render()
    {
        return view('livewire.admin.psb.hasil-ujian-santri', [
            'santriList' => $this->getSantriList(),
            'tipeOptions' => $this->getTipeOptions()
        ]);
    }
}
