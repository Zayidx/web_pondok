<?php

namespace App\Livewire\Admin\PSB;

use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\Periode;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardDaftarUlang extends Component
{
    use WithPagination;

    #[Title('Dashboard Daftar Ulang')]
    
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedRegistration = null;
    public $showDetailModal = false;
    public $showEmptyProofModal = false;
    public $filters = [
        'status' => '',
        'tanggal' => ''
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'filters' => ['except' => ['status' => '', 'tanggal' => '']],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
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
        $this->reset(['search', 'filters', 'sortField', 'sortDirection']);
        $this->resetPage();
    }

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

    public function verifyRegistration($id)
    {
        try {
            DB::beginTransaction();
            
            $registration = PendaftaranSantri::findOrFail($id);
            if ($registration) {
                // Update santri status to diterima
                $registration->status_santri = 'diterima';
                $registration->save();

                DB::commit();

                $this->dispatch('alert', [
                    'type' => 'success',
                    'message' => 'Pendaftaran ulang berhasil diverifikasi dan santri diterima'
                ]);

                // Close modal if it's open
                if ($this->showDetailModal) {
                    $this->closeModal();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat memverifikasi pendaftaran ulang'
            ]);
        }
    }

    public function rejectRegistration($id)
    {
        try {
            DB::beginTransaction();
            
            $registration = PendaftaranSantri::findOrFail($id);
            if ($registration) {
                // Reset all daftar ulang related fields
                $registration->status_pembayaran = null;
                $registration->nominal_pembayaran = null;
                $registration->tanggal_pembayaran = null;
                $registration->bank_pengirim = null;
                $registration->nama_pengirim = null;
                
                // Delete bukti pembayaran file if exists
                if ($registration->bukti_pembayaran && Storage::disk('public')->exists($registration->bukti_pembayaran)) {
                    Storage::disk('public')->delete($registration->bukti_pembayaran);
                }
                $registration->bukti_pembayaran = null;
                
                // Keep status as daftar_ulang so they can try again
                $registration->save();

                DB::commit();

                $this->dispatch('alert', [
                    'type' => 'success',
                    'message' => 'Pendaftaran ulang ditolak. Santri dapat mengupload ulang bukti pembayaran.'
                ]);

                // Close modal if it's open
                if ($this->showDetailModal) {
                    $this->closeModal();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat menolak pendaftaran ulang'
            ]);
        }
    }

    public function viewPaymentProof($id)
    {
        $registration = PendaftaranSantri::find($id);
        if ($registration) {
            if ($registration->bukti_pembayaran) {
                $this->dispatch('openNewTab', ['url' => asset('storage/' . $registration->bukti_pembayaran)]);
            } else {
                $this->showEmptyProofModal = true;
            }
        }
    }

    public function closeEmptyProofModal()
    {
        $this->showEmptyProofModal = false;
    }

    public function terimaSantri($id)
    {
        $registration = PendaftaranSantri::find($id);
        if ($registration) {
            $registration->status_santri = 'diterima';
            $registration->save();

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Santri berhasil diterima'
            ]);
        }
    }

    public function tolakSantri($id)
    {
        $registration = PendaftaranSantri::find($id);
        if ($registration) {
            $registration->status_santri = 'ditolak';
            $registration->save();

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Santri berhasil ditolak'
            ]);
        }
    }

    public function getRegistrationsProperty()
    {
        return PendaftaranSantri::where('status_santri', 'daftar_ulang')
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filters['status'], function($query) {
                $query->where('status_pembayaran', $this->filters['status']);
            })
            ->when($this->filters['tanggal'], function($query) {
                $query->whereDate('created_at', $this->filters['tanggal']);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getStatisticsProperty()
    {
        $baseQuery = PendaftaranSantri::where('status_santri', 'daftar_ulang');

        return [
            'total' => $baseQuery->count(),
            'pending' => $baseQuery->where('status_pembayaran', 'pending')->count(),
            'verified' => PendaftaranSantri::where('status_santri', 'daftar_ulang_diterima')->count(),
            'rejected' => PendaftaranSantri::where('status_santri', 'daftar_ulang_ditolak')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.psb.dashboard-daftar-ulang', [
            'registrations' => $this->registrations,
            'statistics' => $this->statistics
        ]);
    }
} 