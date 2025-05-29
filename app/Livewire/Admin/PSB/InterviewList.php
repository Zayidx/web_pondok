<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InterviewList extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $kota = '';
    public $status_santri = '';
    public $tipeFilter = '';
    public $jam_wawancara = '';
    public $tanggal_wawancara = '';
    public $lokasi_offline = '';
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
        'jam_wawancara' => ['except' => ''],
        'tanggal_wawancara' => ['except' => ''],
        'lokasi_offline' => ['except' => ''],
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

    public function updatingJamWawancara()
    {
        Log::info('Jam wawancara filter updated to: ' . $this->jam_wawancara);
        $this->resetPage();
    }

    public function updatingTanggalWawancara()
    {
        Log::info('Tanggal wawancara filter updated to: ' . $this->tanggal_wawancara);
        $this->resetPage();
    }

    public function updatingLokasiOffline()
    {
        Log::info('Lokasi offline filter updated to: ' . $this->lokasi_offline);
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

    public function openEditModal($santriId)
    {
        Log::info('openEditModal called with santriId: ' . $santriId);
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            if ($santri->status_santri !== 'diterima' || !$santri->tanggal_wawancara) {
                Log::warning('Invalid santri for edit: status=' . $santri->status_santri . ', tanggal_wawancara=' . ($santri->tanggal_wawancara ?? 'null'));
                session()->flash('error', 'Jadwal wawancara tidak dapat diedit untuk santri ini.');
                return;
            }

            $this->selectedSantriId = $santriId;
            $this->interviewForm = [
                'tanggal_wawancara' => $santri->tanggal_wawancara ? \Carbon\Carbon::parse($santri->tanggal_wawancara)->format('Y-m-d') : '',
                'jam_wawancara' => $santri->jam_wawancara ?? '',
                'mode' => $santri->mode ?? 'offline',
                'link_online' => $santri->link_online,
                'lokasi_offline' => $santri->lokasi_offline,
            ];
            Log::info('Edit modal opened with data: ', $this->interviewForm);
            $this->interviewModal = true;
            $this->resetValidation();
        } catch (\Exception $e) {
            Log::error('Error opening edit modal: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal membuka modal edit: ' . $e->getMessage());
        }
    }

    public function saveInterview()
    {
        Log::info('saveInterview called with data: ', $this->interviewForm);

        try {
            $this->validate([
                'interviewForm.tanggal_wawancara' => 'required|date',
                'interviewForm.jam_wawancara' => 'required',
                'interviewForm.mode' => 'required|in:online,offline',
                'interviewForm.link_online' => 'nullable|url|required_if:interviewForm.mode,online',
                'interviewForm.lokasi_offline' => 'nullable|string|max:255|required_if:interviewForm.mode,offline',
            ]);
            Log::info('Validation passed for edit interview, santri ID: ' . $this->selectedSantriId);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for edit interview: ', $e->errors());
            session()->flash('error', 'Validasi gagal: ' . implode(', ', array_merge(...array_values($e->errors()))));
            return;
        }

        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($this->selectedSantriId);
            if ($santri->status_santri !== 'diterima') {
                throw new \Exception('Santri ini tidak dalam status diterima.');
            }

            $updateData = [
                'tanggal_wawancara' => $this->interviewForm['tanggal_wawancara'],
                'jam_wawancara' => $this->interviewForm['jam_wawancara'],
                'mode' => $this->interviewForm['mode'],
                'link_online' => $this->interviewForm['mode'] === 'online' ? $this->interviewForm['link_online'] : null,
                'lokasi_offline' => $this->interviewForm['mode'] === 'offline' ? $this->interviewForm['lokasi_offline'] : null,
            ];

            Log::info('Updating interview schedule for santri ID: ' . $santri->id, $updateData);
            $updated = $santri->update($updateData);
            Log::info('Update result for santri ID: ' . $santri->id, ['updated' => $updated]);

            $santri->refresh();
            Log::info('Santri after update: ', $santri->toArray());

            $dbRecord = DB::table('psb_pendaftaran_santri')->where('id', $santri->id)->first();
            Log::info('Raw database record for santri ID: ' . $santri->id, (array) $dbRecord);

            DB::commit();
            $this->interviewModal = false;
            session()->flash('success', 'Jadwal wawancara berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in saveInterview: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal memperbarui jadwal: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->interviewModal = false;
        $this->reset(['interviewForm', 'selectedSantriId']);
    }

    public function render()
    {
        try {
            $query = PendaftaranSantri::query()
                ->with('wali')
                ->whereNotNull('tanggal_wawancara')
                ->where('status_santri', 'diterima');

            $query->when($this->search, function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%');
            })->when($this->kota, function ($q) {
                $q->whereHas('wali', function ($subQ) {
                    $subQ->where('alamat', 'like', '%' . $this->kota . '%');
                });
            })->when($this->status_santri, function ($q) {
                $q->where('status_santri', $this->status_santri);
            })->when($this->tipeFilter, function ($q) {
                $q->where('tipe_pendaftaran', $this->tipeFilter);
            })->when($this->jam_wawancara, function ($q) {
                $q->where('jam_wawancara', 'like', '%' . $this->jam_wawancara . '%');
            })->when($this->tanggal_wawancara, function ($q) {
                $q->whereDate('tanggal_wawancara', $this->tanggal_wawancara);
            })->when($this->lokasi_offline, function ($q) {
                $q->where('lokasi_offline', 'like', '%' . $this->lokasi_offline . '%');
            });

            Log::info('Interview query parameters: ', [
                'search' => $this->search,
                'kota' => $this->kota,
                'status_santri' => $this->status_santri,
                'tipeFilter' => $this->tipeFilter,
                'jam_wawancara' => $this->jam_wawancara,
                'tanggal_wawancara' => $this->tanggal_wawancara,
                'lokasi_offline' => $this->lokasi_offline,
                'sortField' => $this->sortField,
                'sortDirection' => $this->sortDirection,
            ]);

            $interviews = $query->orderBy($this->sortField, $this->sortDirection)
                               ->paginate($this->perPage);

            Log::info('Interviews retrieved: ', ['count' => $interviews->total(), 'data' => $interviews->items()]);

            $rawResults = DB::table('psb_pendaftaran_santri')
                ->whereNotNull('tanggal_wawancara')
                ->where('status_santri', 'diterima')
                ->get()
                ->toArray();
            Log::info('Raw database interviews: ', ['count' => count($rawResults), 'data' => $rawResults]);

            return view('livewire.admin.psb.interview-list', [
                'interviews' => $interviews,
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
        } catch (\Exception $e) {
            Log::error('Error in InterviewList render: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal memuat daftar wawancara: ' . $e->getMessage());
            return view('livewire.admin.psb.interview-list', [
                'interviews' => collect([])->paginate($this->perPage),
                'statusSantriOptions' => [],
                'tipeOptions' => [],
            ]);
        }
    }
}