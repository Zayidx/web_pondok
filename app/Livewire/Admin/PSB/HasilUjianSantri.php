<?php

namespace App\Livewire\Admin\PSB;

use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\Ujian;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class HasilUjianSantri extends Component
{
    use WithPagination;

    #[Title('Hasil Ujian Santri')]

    public $perPage = 10;
    public $search = '';
    public $searchAlamat = '';
    public $filters = [
        'tipe' => '',
        'nilai' => ''
    ];
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedSantri = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'searchAlamat' => ['except' => ''],
        'filters' => ['except' => ['tipe' => '', 'nilai' => '']],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function mount()
    {
        
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
        $this->reset([
            'search',
            'searchAlamat',
            'filters',
            'sortField',
            'sortDirection'
        ]);
    }

    public function getSantriList()
    {
        $query = PendaftaranSantri::where('status_santri', 'sedang_ujian')
            ->with(['hasilUjians' => function($query) {
                $query->select('id', 'santri_id', 'nilai_akhir', 'ujian_id');
            }])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%')
                      ->orWhere('asal_sekolah', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->searchAlamat, function($query) {
                $query->whereHas('wali', function($q) {
                    $q->where('alamat', 'like', '%' . $this->searchAlamat . '%');
                });
            })
            ->when($this->filters['tipe'], function($query) {
                $query->where('tipe_pendaftaran', $this->filters['tipe']);
            });

        // Add subquery for average score
        $query->addSelect([
            'rata_nilai' => HasilUjian::selectRaw('COALESCE(AVG(nilai_akhir), 0)')
            ->whereColumn('santri_id', 'psb_pendaftaran_santri.id')

        ]);

        // Apply score sorting if selected
        if ($this->filters['nilai'] === 'highest') {
            $query->orderBy('rata_nilai', 'desc');
        } elseif ($this->filters['nilai'] === 'lowest') {
            $query->orderBy('rata_nilai', 'asc');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->paginate($this->perPage);
    }

    public function getNilaiMapel($santri)
    {
        $nilaiPerMapel = [];
        foreach ($santri->hasilUjians as $hasil) {
            $nilaiPerMapel[$hasil->ujian->mata_pelajaran] = $hasil->nilai_akhir;
        }
        return $nilaiPerMapel;
    }

    public function getTotalNilai($santri)
    {
        if ($santri->hasilUjians->isEmpty()) {
            return 0;
        }
        return round($santri->hasilUjians->avg('nilai_akhir'), 2);
    }

    public function terimaSantri($id)
    {
        $santri = PendaftaranSantri::findOrFail($id);
        $santri->update([
            'status' => 'diterima',
            'status_santri' => 'daftar_ulang'
        ]);
        session()->flash('success', 'Santri berhasil diterima dan akan melakukan pendaftaran ulang');
    }

    public function tolakSantri($id)
    {
        $santri = PendaftaranSantri::findOrFail($id);
        $santri->update([
            'status' => 'ditolak',
            'status_santri' => 'ditolak'
        ]);
        session()->flash('success', 'Santri berhasil ditolak');
    }

    public function getTipeOptions()
    {
        return [
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
 
 
 
 
 