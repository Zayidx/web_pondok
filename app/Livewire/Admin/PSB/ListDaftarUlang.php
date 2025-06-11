<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

#[Title('Dashboard Daftar Ulang')]
class ListDaftarUlang extends Component
{
    use WithPagination;

    // Properti untuk filter, sorting, dan pagination
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filters = [
        'tipe' => '',
        'status' => '',
        'urutan' => 'terbaru',
    ];

    // Properti untuk modal
    public bool $showDetailModal = false;
    public bool $showProofModal = false;
    public ?PendaftaranSantri $selectedRegistration;
    public ?string $proofImageUrl = null;
    
    protected $paginationTheme = 'bootstrap';

    // Reset halaman jika ada filter baru
    public function updating($key)
    {
        if (in_array(explode('.', $key)[0], ['search', 'perPage', 'filters'])) {
            $this->resetPage();
        }
    }

    public function sortBy($field)
    {
        $this->filters['urutan'] = ''; 

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
            $this->sortField = $field;
        }
    }

    public function resetFilters()
    {
        $this->reset('search', 'filters');
        $this->resetPage();
    }
    
    // --- METODE-METODE AKSI YANG DITAMBAHKAN ---

    public function showDetail($id)
    {
        $this->selectedRegistration = PendaftaranSantri::find($id);
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedRegistration = null;
    }

    public function viewPaymentProof($id)
    {
        $registration = PendaftaranSantri::find($id);
        if ($registration?->bukti_pembayaran) {
            $this->proofImageUrl = Storage::url($registration->bukti_pembayaran);
            $this->showProofModal = true;
        } else {
            session()->flash('error', 'Bukti pembayaran untuk santri ini tidak ditemukan.');
        }
    }

    public function closeProofModal()
    {
        $this->showProofModal = false;
        $this->proofImageUrl = null;
    }

    public function verifyRegistration($id)
    {
        $registration = PendaftaranSantri::findOrFail($id);
        $registration->update([
            'status_santri' => 'diterima',
            'status_pembayaran' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);
        session()->flash('success', 'Pendaftaran ulang ' . $registration->nama_lengkap . ' telah diterima.');
        $this->closeModal();
    }

    public function rejectRegistration($id)
    {
        $registration = PendaftaranSantri::findOrFail($id);
        $registration->update([
            'status_pembayaran' => 'rejected',
            'bukti_pembayaran' => null, // Hapus bukti agar bisa upload ulang
        ]);
        session()->flash('success', 'Bukti pembayaran ' . $registration->nama_lengkap . ' ditolak.');
        $this->closeModal();
    }

    // Computed property untuk opsi filter program
    #[Computed]
    public function tipeOptions()
    {
        return [
            'reguler' => 'Reguler',
            'olimpiade' => 'Olimpiade',
            'internasional' => 'Internasional'
        ];
    }
    
    public function render()
    {
        $query = PendaftaranSantri::whereIn('status_santri', ['diterima', 'daftar_ulang'])
            ->whereNotNull('bukti_pembayaran')
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filters['tipe'], function ($query) {
                $query->where('tipe_pendaftaran', $this->filters['tipe']);
            })
            ->when($this->filters['status'], function ($query) {
                $query->where('status_pembayaran', $this->filters['status']);
            });

        // Logika sorting
        if ($this->filters['urutan'] === 'terbaru') {
            $query->orderBy('created_at', 'desc');
        } elseif ($this->filters['urutan'] === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return view('livewire.admin.psb.list-daftar-ulang', [
            'registrations' => $query->paginate($this->perPage),
        ]);
    }
}