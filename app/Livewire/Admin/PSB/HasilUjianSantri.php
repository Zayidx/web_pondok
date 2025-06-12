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
        'tipe' => '',
        'nilai' => '',
        // Filter statusUjian dan statusPendaftaran dihapus karena fokus hanya pada 'sedang_ujian'
    ];
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedSantri = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'searchAlamat' => ['except' => ''],
        // Query string diperbarui untuk hanya menyertakan filter yang tersisa
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

    // Computed properties untuk opsi status ujian dan pendaftaran dihapus
    // karena filter utama sekarang ditetapkan secara langsung pada query.

    public function getSantriList()
    {
        // Perubahan: Hanya menampilkan santri dengan status_santri 'sedang_ujian'
        // Filter ini bersifat mutlak dan tidak dapat diubah oleh dropdown lain di halaman ini.
        $query = PendaftaranSantri::where('status_santri', 'sedang_ujian')
            ->with(['hasilUjians' => function($query) {
                // Memuat hasil ujian yang relevan dan menyertakan status
                $query->select('id', 'santri_id', 'nilai_akhir', 'ujian_id', 'status');
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
            // Filter statusUjian dan statusPendaftaran dihapus dari logika query ini

        // Menambahkan kolom computed total_nilai_keseluruhan dari semua ujian yang sudah selesai
        $query->addSelect([
            'total_nilai_keseluruhan' => HasilUjian::selectRaw('COALESCE(SUM(nilai_akhir), 0)')
                                                ->whereColumn('santri_id', 'psb_pendaftaran_santri.id')
                                                ->where('status', 'selesai') // Hanya hitung yang sudah selesai dinilai admin
        ]);

        if ($this->filters['nilai'] === 'highest') {
            $query->orderBy('total_nilai_keseluruhan', 'desc');
        } elseif ($this->filters['nilai'] === 'lowest') {
            $query->orderBy('total_nilai_keseluruhan', 'asc');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->paginate($this->perPage);
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

    // getTipeOptions tetap ada karena masih digunakan oleh filter 'tipe'
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
            'tipeOptions' => $this->getTipeOptions(),
            // statusUjianOptions dan statusPendaftaranOptions tidak lagi diteruskan ke view
        ]);
    }
}
