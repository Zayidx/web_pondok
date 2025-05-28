<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WaliSantri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShowRegistrations extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $kota = '';
    public $status_santri = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $interviewModal = false;
    public $rejectModal = false;
    public $selectedSantriId;

    public $interviewForm = [
        'tanggal_wawancara' => '',
        'jam_wawancara' => '',
        'mode' => 'offline',
        'link_online' => '',
        'lokasi_offline' => '',
    ];

    public $rejectForm = [
        'reason' => '',
    ];

    protected $queryString = [
        'perPage' => ['except' => 5],
        'search' => ['except' => ''],
        'kota' => ['except' => ''],
        'status_santri' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKota()
    {
        $this->resetPage();
    }

    public function updatingStatusSantri()
    {
        Log::info('Status santri updated to: ' . $this->status_santri);
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        Log::info('PerPage updated to: ' . $this->perPage);
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

    public function openInterviewModal($santriId)
    {
        Log::info('openInterviewModal called with santriId: ' . $santriId);
        $this->selectedSantriId = $santriId;
        $this->interviewForm = [
            'tanggal_wawancara' => '',
            'jam_wawancara' => '',
            'mode' => 'offline',
            'link_online' => '',
            'lokasi_offline' => '',
        ];
        $this->interviewModal = true;
        $this->resetValidation();
    }

    public function openRejectModal($santriId)
    {
        $this->selectedSantriId = $santriId;
        $this->rejectForm = ['reason' => ''];
        $this->rejectModal = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->interviewModal = false;
        $this->rejectModal = false;
    }

    public function saveInterview()
    {
        Log::info('saveInterview called with data: ', $this->interviewForm);

        $this->validate([
            'interviewForm.tanggal_wawancara' => 'required|date|after:today',
            'interviewForm.jam_wawancara' => 'required',
            'interviewForm.mode' => 'required|in:online,offline',
            'interviewForm.link_online' => 'required_if:interviewForm.mode,online|url|nullable',
            'interviewForm.lokasi_offline' => 'required_if:interviewForm.mode,offline|nullable',
        ]);

        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($this->selectedSantriId);
            Log::info('Updating status_santri to diterima for santri ID: ' . $santri->id);
            $santri->update([
                'status_santri' => 'diterima',
                'tanggal_wawancara' => $this->interviewForm['tanggal_wawancara'],
                'jam_wawancara' => $this->interviewForm['jam_wawancara'],
                'mode' => $this->interviewForm['mode'],
                'link_online' => $this->interviewForm['link_online'],
                'lokasi_offline' => $this->interviewForm['lokasi_offline'],
            ]);

            DB::commit();
            $this->interviewModal = false;
            session()->flash('success', 'Santri diterima dan jadwal wawancara disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in saveInterview: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function reject()
    {
        $this->validate([
            'rejectForm.reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($this->selectedSantriId);
            $santri->update([
                'status_santri' => 'ditolak',
                'reason_rejected' => $this->rejectForm['reason'],
                'tanggal_wawancara' => null,
                'jam_wawancara' => null,
                'mode' => null,
                'link_online' => null,
                'lokasi_offline' => null,
            ]);

            DB::commit();
            $this->rejectModal = false;
            session()->flash('success', 'Santri ditolak dengan alasan yang diberikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in reject: ' . $e->getMessage());
            session()->flash('error', 'Gagal menolak santri: ' . $e->getMessage());
        }
    }

    public function cancelStatus($santriId)
    {
        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            $santri->update([
                'status_santri' => null,
                'reason_rejected' => null,
                'tanggal_wawancara' => null,
                'jam_wawancara' => null,
                'mode' => null,
                'link_online' => null,
                'lokasi_offline' => null,
            ]);

            DB::commit();
            session()->flash('success', 'Status santri dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in cancelStatus: ' . $e->getMessage());
            session()->flash('error', 'Gagal membatalkan status: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $registrations = PendaftaranSantri::query()
            ->with('wali')
            ->when($this->search, function ($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%');
            })
            ->when($this->kota, function ($query) {
                $query->whereHas('wali', function ($q) {
                    $q->where('alamat', 'like', '%' . $this->kota . '%');
                });
            })
            ->when($this->status_santri, function ($query) {
                $query->where('status_santri', $this->status_santri);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.psb.show-registrations', [
            'registrations' => $registrations,
            'statusSantriOptions' => [
                'reguler' => 'Reguler',
                'olimpiade' => 'Olimpiade',
                'internasional' => 'Internasional',
                'diterima' => 'Diterima',
                'ditolak' => 'Ditolak',
            ],
        ]);
    }
}