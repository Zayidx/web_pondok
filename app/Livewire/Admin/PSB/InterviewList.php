<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Carbon\Carbon;

class InterviewList extends Component
{
    use WithPagination;
    #[Title('Halaman List Wawancara Santri PPDB')]
    
    public $perPage = 10;
    public $search = '';
    public $searchLokasi = '';
    public $sortNisn = '';
    public $filterTanggal = '';
    public $sortJam = '';
    public $sortMode = '';
    public $sortField = 'tanggal_wawancara';
    public $sortDirection = 'desc';
    public $showEditModal = false;
    public $selectedSantriId;
    public $interviewForm = [
        'tanggal_wawancara' => '',
        'jam_wawancara' => '',
        'mode' => 'offline',
        'link_online' => '',
        'lokasi_offline' => '',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'tanggal_wawancara'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        $this->interviewForm['tanggal_wawancara'] = now()->format('Y-m-d');
        $this->interviewForm['jam_wawancara'] = '09:00';
    }

    public function updatingSearch()
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

    public function openEditModal($santriId)
    {
            $santri = PendaftaranSantri::findOrFail($santriId);
        if ($santri->status_santri !== 'wawancara') {
            session()->flash('error', 'Hanya santri dengan status wawancara yang dapat diedit jadwalnya.');
                return;
            }

            $this->selectedSantriId = $santriId;
            $this->interviewForm = [
            'tanggal_wawancara' => Carbon::parse($santri->tanggal_wawancara)->format('Y-m-d'),
            'jam_wawancara' => Carbon::parse($santri->tanggal_wawancara)->format('H:i'),
                'mode' => $santri->mode ?? 'offline',
            'link_online' => $santri->link_online,
            'lokasi_offline' => $santri->lokasi_offline,
            ];
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedSantriId = null;
        $this->reset(['interviewForm']);
    }

    public function saveInterview()
    {
            $this->validate([
            'interviewForm.tanggal_wawancara' => 'required|date|after_or_equal:today',
            'interviewForm.jam_wawancara' => 'required',
            'interviewForm.mode' => 'required|in:offline,online',
            'interviewForm.link_online' => 'required_if:interviewForm.mode,online',
            'interviewForm.lokasi_offline' => 'required_if:interviewForm.mode,offline',
            ]);

        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($this->selectedSantriId);
            if ($santri->status_santri !== 'wawancara') {
                throw new \Exception('Santri ini tidak dalam status wawancara.');
            }

            $updateData = [
                'tanggal_wawancara' => $this->interviewForm['tanggal_wawancara'] . ' ' . $this->interviewForm['jam_wawancara'],
                'mode' => $this->interviewForm['mode'],
                'link_online' => $this->interviewForm['link_online'],
                'lokasi_offline' => $this->interviewForm['lokasi_offline'],
            ];

            $santri->update($updateData);
            DB::commit();
            
            $this->showEditModal = false;
            session()->flash('success', 'Jadwal wawancara berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal memperbarui jadwal: ' . $e->getMessage());
        }
    }

    public function acceptSantri($santriId)
    {
        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            if ($santri->status_santri !== 'wawancara') {
                throw new \Exception('Hanya santri dengan status wawancara yang dapat diterima.');
            }

            $santri->update([
                'status_santri' => 'sedang_ujian'
            ]);

            DB::commit();
            session()->flash('success', 'Santri berhasil diterima dan status diubah menjadi sedang ujian.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menerima santri: ' . $e->getMessage());
        }
    }

    public function rejectSantri($santriId)
    {
        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            if ($santri->status_santri !== 'wawancara') {
                throw new \Exception('Hanya santri dengan status wawancara yang dapat ditolak.');
            }

            $santri->update([
                'status_santri' => 'ditolak'
            ]);

            DB::commit();
            session()->flash('success', 'Santri telah ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menolak santri: ' . $e->getMessage());
        }
    }

    public function getInterviewsProperty()
    {
        return PendaftaranSantri::query()
            ->where('status_santri', 'wawancara')
                ->whereNotNull('tanggal_wawancara')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->searchLokasi, function ($query) {
                $query->where(function ($q) {
                    $q->where('lokasi_offline', 'like', '%' . $this->searchLokasi . '%')
                        ->orWhere('link_online', 'like', '%' . $this->searchLokasi . '%');
                });
            })
            ->when($this->sortNisn, function ($query) {
                $query->orderBy('nisn', $this->sortNisn);
            })
            ->when($this->filterTanggal, function ($query) {
                $query->whereDate('tanggal_wawancara', $this->filterTanggal);
            })
            ->when($this->sortJam, function ($query) {
                $query->orderByRaw('TIME(tanggal_wawancara) ' . $this->sortJam);
            })
            ->when($this->sortMode, function ($query) {
                $query->where('mode', $this->sortMode);
            })
            ->orderBy($this->sortField, $this->sortDirection)
                               ->paginate($this->perPage);
    }

    public function render()
    {
            return view('livewire.admin.psb.interview-list', [
            'interviews' => $this->interviews
            ]);
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'searchLokasi',
            'sortNisn',
            'filterTanggal',
            'sortJam',
            'sortMode'
        ]);
    }
}