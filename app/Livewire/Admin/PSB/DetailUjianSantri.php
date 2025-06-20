<?php

namespace App\Livewire\Admin\PSB;

use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\Ujian;
use App\Models\PSB\JawabanUjian; // Pastikan ini di-import
use App\Models\PSB\Soal;       // Pastikan ini di-import
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;

#[Title('Detail Ujian Santri')]
class DetailUjianSantri extends Component
{
    use WithPagination;

    public $santriId;
    public $santri;
    public $ujianList;
    public $search = '';
    public $sortField = 'tanggal_ujian';
    public $sortDirection = 'desc';

    public function mount($id)
    {
        $this->santriId = $id;
        // Muat relasi 'dokumen' untuk mendapatkan akses ke foto
        $this->santri = PendaftaranSantri::with('dokumen')->findOrFail($id); 
        $this->loadUjianList();
    }

    public function loadUjianList()
    {
        $query = Ujian::query()
            ->with(['hasilUjians' => function ($query) {
                $query->where('santri_id', $this->santriId);
            }])
            ->when($this->search, function ($query) {
                $query->where('nama_ujian', 'like', '%' . $this->search . '%')
                    ->orWhere('mata_pelajaran', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $this->ujianList = $query->get();
    }

    public function updatingSearch()
    {
        $this->loadUjianList();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->loadUjianList();
    }

    public function render()
    {
        return view('livewire.admin.psb.detail-ujian-santri', [
            'santri' => $this->santri,
            'ujianList' => $this->ujianList,
            'totalNilai' => $this->santri->total_nilai_semua_ujian,
            'rataRata' => $this->santri->rata_rata_ujian
        ]);
    }
}