<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;

class ShowRegistrations extends Component
{
   
    use WithPagination;
    #[Title('Halaman List Santri PPDB')]
    public $perPage = 5;
    public $search = '';
    public $kota = '';
    public $status_santri = '';
    public $tipeFilter = '';
    public $sortField = 'nama_lengkap';
    public $sortDirection = 'asc';
    public $interviewModal = false;
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
        'kota' => ['except' => ''],
        'status_santri' => ['except' => ''],
        'tipeFilter' => ['except' => ''],
        'perPage' => ['except' => 5],
        'sortField' => ['except' => 'nama_lengkap'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        Log::info('Search updated to: ' . $this->search);
        $this->resetPage();
    }

    public function updatingKota()
    {
        Log::info('Kota filter updated to: ' . $this->kota);
        $this->resetPage();
    }

    public function updatingStatusSantri()
    {
        Log::info('Status santri filter updated to: ' . $this->status_santri);
        $this->resetPage();
    }

    public function updatingTipeFilter()
    {
        Log::info('Tipe filter updated to: ' . $this->tipeFilter);
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
        Log::info("Sorting by $field in $this->sortDirection order");
    }

    public function openInterviewModal($santriId)
    {
        Log::info('openInterviewModal called with santriId: ' . $santriId);
        $santri = PendaftaranSantri::findOrFail($santriId);
        if ($santri->status_santri !== 'menunggu') {
            Log::warning('Santri ID: ' . $santriId . ' has status: ' . $santri->status_santri);
            session()->flash('error', 'Jadwal wawancara hanya dapat dibuat untuk santri dengan status menunggu.');
            return;
        }

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

    public function saveInterview()
    {
        Log::info('saveInterview called with data: ', $this->interviewForm);

        try {
            $this->validate([
                'interviewForm.tanggal_wawancara' => 'required|date',
                'interviewForm.jam_wawancara' => 'required',
                'interviewForm.mode' => 'required|in:online,offline',
                'interviewForm.link_online' => 'required_if:interviewForm.mode,online|url|nullable',
                'interviewForm.lokasi_offline' => 'required_if:interviewForm.mode,offline|string|max:255|nullable',
            ]);
            Log::info('Validation passed for santri ID: ' . $this->selectedSantriId);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ', $e->errors());
            session()->flash('error', 'Validasi gagal: ' . implode(', ', array_merge(...array_values($e->errors()))));
            return;
        }

        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($this->selectedSantriId);
            if ($santri->status_santri !== 'menunggu') {
                throw new \Exception('Santri ini tidak dalam status menunggu.');
            }

            $updateData = [
                'status_santri' => 'diterima',
                'tanggal_wawancara' => $this->interviewForm['tanggal_wawancara'],
                'jam_wawancara' => $this->interviewForm['jam_wawancara'],
                'mode' => $this->interviewForm['mode'],
                'link_online' => $this->interviewForm['link_online'],
                'lokasi_offline' => $this->interviewForm['lokasi_offline'],
                'tipe_pendaftaran' => $santri->tipe_pendaftaran ?? 'reguler', // Ensure tipe_pendaftaran is set
            ];

            Log::info('Attempting to update santri ID: ' . $santri->id, $updateData);
            $updated = $santri->update($updateData);
            Log::info('Update result for santri ID: ' . $santri->id, ['updated' => $updated]);

            // Verify database state
            $santri->refresh();
            Log::info('Santri after update: ', $santri->toArray());

            // Check database directly
            $dbRecord = DB::table('psb_pendaftaran_santri')->where('id', $santri->id)->first();
            Log::info('Raw database record for santri ID: ' . $santri->id, (array) $dbRecord);

            if ($dbRecord->status_santri !== 'diterima' || !$dbRecord->tanggal_wawancara) {
                throw new \Exception('Data gagal tersimpan di database.');
            }

            DB::commit();
            $this->interviewModal = false;
            session()->flash('success', 'Santri diterima dan jadwal wawancara berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in saveInterview: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal menyimpan jadwal: ' . $e->getMessage());
        }
    }

    public function reject($santriId)
    {
        Log::info('reject called with santriId: ' . $santriId);
        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            if ($santri->status_santri !== 'menunggu') {
                throw new \Exception('Santri ini tidak dalam status menunggu.');
            }

            Log::info('Rejecting santri ID: ' . $santri->id);
            $updated = $santri->update([
                'status_santri' => 'ditolak',
                'reason_rejected' => null,
                'tanggal_wawancara' => null,
                'jam_wawancara' => null,
                'mode' => null,
                'link_online' => null,
                'lokasi_offline' => null,
            ]);

            Log::info('Reject update result for santri ID: ' . $santri->id, ['updated' => $updated]);
            $santri->refresh();
            Log::info('Santri after reject: ', $santri->toArray());

            DB::commit();
            session()->flash('success', 'Santri berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in reject: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal menolak santri: ' . $e->getMessage());
        }
    }

    public function cancelStatus($santriId)
    {
        Log::info('cancelStatus called with santriId: ' . $santriId);
        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            if (!in_array($santri->status_santri, ['diterima', 'ditolak'])) {
                throw new \Exception('Status santri ini tidak dapat dibatalkan.');
            }

            Log::info('Canceling status for santri ID: ' . $santri->id);
            $updated = $santri->update([
                'status_santri' => 'menunggu',
                'tanggal_wawancara' => null,
                'jam_wawancara' => null,
                'mode' => null,
                'link_online' => null,
                'lokasi_offline' => null,
                'reason_rejected' => null,
            ]);

            Log::info('Cancel update result for santri ID: ' . $santri->id, ['updated' => $updated]);
            $santri->refresh();
            Log::info('Santri after cancel: ', $santri->toArray());

            DB::commit();
            session()->flash('success', 'Status santri berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in cancelStatus: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal membatalkan status: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->interviewModal = false;
        $this->reset(['interviewForm', 'selectedSantriId']);
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
            ->when($this->tipeFilter, function ($query) {
                $query->where('tipe_pendaftaran', $this->tipeFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.psb.show-registrations', [
            'registrations' => $registrations,
            'statusSantriOptions' => [
                '' => 'Dibatalkan',
                'menunggu' => 'Menunggu',
                'diterima' => 'Diterima',
                'ditolak' => 'Ditolak',
            ],
            'tipeOptions' => [
                'reguler' => 'Reguler',
                'olimpiade' => 'Olimpiade',
                'internasional' => 'Internasional',
            ],
        ]);
    }
}