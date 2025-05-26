<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\JadwalWawancara;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InterviewList extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $tanggalWawancara = '';
    public $jamWawancara = '';
    public $lokasiWawancara = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $editInterviewModal = false;
    public $rejectModal = false;
    public $selectedInterviewId;
    public $selectedSantriId;
    public $editInterviewForm = [
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
        'tanggalWawancara' => ['except' => ''],
        'jamWawancara' => ['except' => ''],
        'lokasiWawancara' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTanggalWawancara()
    {
        $this->resetPage();
    }

    public function updatingJamWawancara()
    {
        $this->resetPage();
    }

    public function updatingLokasiWawancara()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
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

    public function cancelAcceptance($santriId)
    {
        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            $santri->update(['status_santri' => null, 'reason_rejected' => null]);

            // Hapus jadwal wawancara terkait
            JadwalWawancara::where('santri_id', $santriId)->delete();

            // Hapus data dari tabel santri dan orang tua santri
            \App\Models\Santri::where('nisn', $santri->nisn)->delete();
            \App\Models\OrangTuaSantri::where('santri_id', $santri->id)->delete();

            DB::commit();
            session()->flash('success', 'Status santri dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in cancelAcceptance: ' . $e->getMessage());
            session()->flash('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }

    public function openEditInterviewModal($interviewId)
    {
        $interview = JadwalWawancara::findOrFail($interviewId);
        $this->selectedInterviewId = $interviewId;
        $this->editInterviewForm = [
            'tanggal_wawancara' => $interview->tanggal_wawancara,
            'jam_wawancara' => $interview->jam_wawancara,
            'mode' => $interview->mode,
            'link_online' => $interview->link_online,
            'lokasi_offline' => $interview->lokasi_offline,
        ];
        $this->editInterviewModal = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->editInterviewModal = false;
        $this->rejectModal = false;
    }

    public function updateInterview()
    {
        $this->validate([
            'editInterviewForm.tanggal_wawancara' => 'required|date|after:today',
            'editInterviewForm.jam_wawancara' => 'required',
            'editInterviewForm.mode' => 'required|in:online,offline',
            'editInterviewForm.link_online' => 'required_if:editInterviewForm.mode,online|url|nullable',
            'editInterviewForm.lokasi_offline' => 'required_if:editInterviewForm.mode,offline|nullable',
        ]);

        try {
            $interview = JadwalWawancara::findOrFail($this->selectedInterviewId);
            $interview->update([
                'tanggal_wawancara' => $this->editInterviewForm['tanggal_wawancara'],
                'jam_wawancara' => $this->editInterviewForm['jam_wawancara'],
                'mode' => $this->editInterviewForm['mode'],
                'link_online' => $this->editInterviewForm['link_online'],
                'lokasi_offline' => $this->editInterviewForm['lokasi_offline'],
            ]);

            $this->editInterviewModal = false;
            session()->flash('success', 'Jadwal wawancara berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error in updateInterview: ' . $e->getMessage());
            session()->flash('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function openRejectModal($santriId)
    {
        $this->selectedSantriId = $santriId;
        $this->rejectForm = ['reason' => ''];
        $this->rejectModal = true;
        $this->resetValidation();
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
            ]);

            // Hapus jadwal wawancara terkait
            JadwalWawancara::where('santri_id', $santri->id)->delete();

            // Hapus data dari tabel santri dan orang tua santri
            \App\Models\Santri::where('nisn', $santri->nisn)->delete();
            \App\Models\OrangTuaSantri::where('santri_id', $santri->id)->delete();

            DB::commit();
            $this->rejectModal = false;
            session()->flash('success', 'Santri ditolak dengan alasan yang diberikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in reject: ' . $e->getMessage());
            session()->flash('error', 'Gagal menolak: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $interviews = PendaftaranSantri::query()
            ->where('status_santri', 'diterima')
            ->with('jadwalWawancara')
            ->when($this->search, function ($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%');
            })
            ->when($this->tanggalWawancara, function ($query) {
                $query->whereHas('jadwalWawancara', function ($q) {
                    $q->where('tanggal_wawancara', $this->tanggalWawancara);
                });
            })
            ->when($this->jamWawancara, function ($query) {
                $query->whereHas('jadwalWawancara', function ($q) {
                    $q->where('jam_wawancara', $this->jamWawancara);
                });
            })
            ->when($this->lokasiWawancara, function ($query) {
                $query->whereHas('jadwalWawancara', function ($q) {
                    $q->where('lokasi_offline', 'like', '%' . $this->lokasiWawancara . '%')
                      ->orWhere('link_online', 'like', '%' . $this->lokasiWawancara . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.psb.interview-list', [
            'interviews' => $interviews,
        ]);
    }
}