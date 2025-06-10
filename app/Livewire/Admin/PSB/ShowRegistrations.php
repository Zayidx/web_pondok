<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;

class ShowRegistrations extends Component
{
    use WithPagination;
    #[Title('Halaman List Santri PPDB')]
    public $perPage = 10;
    public $search = '';
    public $searchAlamat = '';
    public $filters = [
        'status' => '',
        'tipe' => ''
    ];
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $interviewModal = false;
    public $selectedSantriId;
    public $editModal = false;
    public $editForm = [
        'nama_lengkap' => '',
        'nisn' => '',
        'tipe_pendaftaran' => '',
    ];

    public $showInterviewModal = false;
    public $interviewForm = [
        'tanggal_wawancara' => '',
        'jam_wawancara' => '',
        'mode' => 'offline',
        'link_online' => '',
        'lokasi_offline' => '',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'searchAlamat' => ['except' => ''],
        'filters' => ['except' => ['status' => '', 'tipe' => '']],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc']
    ];

    protected function rules()
    {
        return [
            'interviewForm.tanggal_wawancara' => 'required|date|after_or_equal:today',
            'interviewForm.jam_wawancara' => 'required',
            'interviewForm.mode' => 'required|in:offline,online',
            'interviewForm.link_online' => 'required_if:interviewForm.mode,online|url|nullable',
            'interviewForm.lokasi_offline' => 'required_if:interviewForm.mode,offline|string|nullable',
        ];
    }

    protected $messages = [
        'interviewForm.tanggal_wawancara.required' => 'Tanggal wawancara harus diisi.',
        'interviewForm.tanggal_wawancara.date' => 'Format tanggal tidak valid.',
        'interviewForm.tanggal_wawancara.after_or_equal' => 'Tanggal wawancara tidak boleh kurang dari hari ini.',
        'interviewForm.jam_wawancara.required' => 'Waktu wawancara harus diisi.',
        'interviewForm.mode.required' => 'Mode wawancara harus dipilih.',
        'interviewForm.mode.in' => 'Mode wawancara tidak valid.',
        'interviewForm.link_online.required_if' => 'Link meeting harus diisi untuk wawancara online.',
        'interviewForm.link_online.url' => 'Format link meeting tidak valid.',
        'interviewForm.lokasi_offline.required_if' => 'Lokasi wawancara harus diisi untuk wawancara offline.',
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

    public function cancelStatus($santriId)
    {
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            
            if (!in_array($santri->status_santri, ['diterima', 'ditolak'])) {
                session()->flash('error', 'Hanya status diterima atau ditolak yang dapat dibatalkan.');
                return;
            }

            $oldStatus = $santri->status_santri;
            $santri->status_santri = 'daftar_ulang';
            $santri->save();

            Log::info('Status cancelled successfully', [
                'santri_id' => $santri->id,
                'old_status' => $oldStatus,
                'new_status' => 'daftar_ulang'
            ]);

            session()->flash('success', 'Status berhasil dibatalkan dan dikembalikan ke tahap daftar ulang.');
        } catch (\Exception $e) {
            Log::error('Failed to cancel status', [
                'santri_id' => $santriId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', 'Terjadi kesalahan saat membatalkan status.');
        }
    }

    public function cancelExam($santriId)
    {
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            
            if ($santri->status_santri !== 'sedang_ujian') {
                session()->flash('error', 'Hanya santri dengan status sedang ujian yang dapat dibatalkan ujiannya.');
                return;
            }

            DB::beginTransaction();

            // Delete related exam records if any
            if ($santri->hasilUjians()->exists()) {
                $santri->hasilUjians()->delete();
            }
            if ($santri->jawabanUjians()->exists()) {
                $santri->jawabanUjians()->delete();
            }

            // Update status back to wawancara
            $santri->status_santri = 'wawancara';
            $santri->save();

            DB::commit();

            Log::info('Exam cancelled successfully', [
                'santri_id' => $santri->id,
                'old_status' => 'sedang_ujian',
                'new_status' => 'wawancara'
            ]);

            session()->flash('success', 'Ujian berhasil dibatalkan dan status dikembalikan ke tahap wawancara.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to cancel exam', [
                'santri_id' => $santriId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', 'Terjadi kesalahan saat membatalkan ujian.');
        }
    }

    public function cancelDaftarUlang($santriId)
    {
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            
            if ($santri->status_santri !== 'daftar_ulang') {
                session()->flash('error', 'Hanya santri dengan status Daftar Ulang yang dapat dibatalkan.');
                return;
            }

            DB::beginTransaction();

            // Delete bukti pembayaran file if exists
            if ($santri->bukti_pembayaran && Storage::disk('public')->exists($santri->bukti_pembayaran)) {
                Storage::disk('public')->delete($santri->bukti_pembayaran);
            }

            // Reset status pembayaran jika ada
            $santri->status_pembayaran = null;
            $santri->nominal_pembayaran = null;
            $santri->tanggal_pembayaran = null;
            $santri->bank_pengirim = null;
            $santri->nama_pengirim = null;
            $santri->bukti_pembayaran = null;
            $santri->catatan_verifikasi = null;

            // Update status back to diterima
            $santri->status_santri = 'diterima';
            $santri->save();

            DB::commit();

            Log::info('Daftar ulang cancelled successfully', [
                'santri_id' => $santri->id,
                'old_status' => 'daftar_ulang',
                'new_status' => 'diterima'
            ]);

            session()->flash('success', 'Daftar ulang berhasil dibatalkan dan status dikembalikan ke tahap diterima.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to cancel daftar ulang', [
                'santri_id' => $santriId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', 'Terjadi kesalahan saat membatalkan daftar ulang.');
        }
    }

    public function openInterviewModal($santriId)
    {
        Log::info('openInterviewModal called with santriId: ' . $santriId);
        $santri = PendaftaranSantri::findOrFail($santriId);

        $this->selectedSantriId = $santriId;
        $this->interviewForm = [
            'tanggal_wawancara' => now()->format('Y-m-d'),
            'jam_wawancara' => '09:00',
            'mode' => 'offline',
            'link_online' => '',
            'lokasi_offline' => 'Ruang Meeting Lt. 2',
        ];
        $this->showInterviewModal = true;
        $this->resetValidation();
    }

    public function closeInterviewModal()
    {
        $this->showInterviewModal = false;
        $this->selectedSantriId = null;
        $this->interviewForm['tanggal_wawancara'] = now()->format('Y-m-d');
        $this->interviewForm['jam_wawancara'] = '09:00';
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

            // Combine date and time into a single datetime
            $tanggalWawancara = \Carbon\Carbon::parse($this->interviewForm['tanggal_wawancara'])
                ->setTimeFromTimeString($this->interviewForm['jam_wawancara'])
                ->format('Y-m-d H:i:s');

            $updateData = [
                'tanggal_wawancara' => $tanggalWawancara,
                'mode' => $this->interviewForm['mode'],
                'link_online' => $this->interviewForm['mode'] === 'online' ? $this->interviewForm['link_online'] : null,
                'lokasi_offline' => $this->interviewForm['mode'] === 'offline' ? $this->interviewForm['lokasi_offline'] : null,
                'status_santri' => 'wawancara'
            ];

            $santri->update($updateData);
            DB::commit();
            
            $this->showInterviewModal = false;
            $this->selectedSantriId = null;
            $this->reset(['interviewForm']);
            session()->flash('success', 'Jadwal wawancara berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save interview schedule', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $updateData ?? null
            ]);
            session()->flash('error', 'Gagal menyimpan jadwal wawancara: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showInterviewModal = false;
        $this->reset(['interviewForm', 'selectedSantriId']);
    }

    public function openEditModal($registrationId)
    {
        Log::info('openEditModal called with registrationId: ' . $registrationId);
        try {
            $registration = PendaftaranSantri::findOrFail($registrationId);
            $this->selectedSantriId = $registrationId;
            $this->editForm = [
                'nama_lengkap' => $registration->nama_lengkap,
                'nisn' => $registration->nisn,
                'tipe_pendaftaran' => $registration->tipe_pendaftaran,
            ];
            Log::info('Edit modal opened with data: ', $this->editForm);
            $this->editModal = true;
            $this->resetValidation();
        } catch (\Exception $e) {
            Log::error('Error in openEditModal: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal membuka modal edit: ' . $e->getMessage());
        }
    }

    public function closeEditModal()
    {
        $this->editModal = false;
        $this->reset(['editForm', 'selectedSantriId']);
    }

    public function saveRegistration()
    {
        Log::info('saveRegistration called with data: ', $this->editForm);

        try {
            $this->validate([
                'editForm.nama_lengkap' => 'required|string|max:255',
                'editForm.nisn' => 'required|string|max:20',
                'editForm.tipe_pendaftaran' => 'required|in:' . implode(',', array_keys($this->tipeOptions)),
            ]);
            Log::info('Validation passed for edit registration, santri ID: ' . $this->selectedSantriId);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for edit registration: ', $e->errors());
            session()->flash('error', 'Validasi gagal: ' . implode(', ', array_merge(...array_values($e->errors()))));
            return;
        }

        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($this->selectedSantriId);

            $updateData = [
                'nama_lengkap' => $this->editForm['nama_lengkap'],
                'nisn' => $this->editForm['nisn'],
                'tipe_pendaftaran' => $this->editForm['tipe_pendaftaran'],
            ];

            Log::info('Updating registration for santri ID: ' . $santri->id, $updateData);
            $updated = $santri->update($updateData);
            Log::info('Update result for santri ID: ' . $santri->id, ['updated' => $updated]);

            $santri->refresh();
            Log::info('Santri after update: ', $santri->toArray());

            DB::commit();
            $this->editModal = false;
            session()->flash('success', 'Data santri berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in saveRegistration: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedInterviewFormMode($value)
    {
        // Reset the corresponding field when mode changes
        if ($value === 'online') {
            $this->interviewForm['lokasi_offline'] = '';
        } else {
            $this->interviewForm['link_online'] = '';
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

    public function cancelInterview($santriId)
    {
        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            
            if (!$santri->tanggal_wawancara) {
                session()->flash('error', 'Santri ini belum memiliki jadwal wawancara.');
                return;
            }

            $updateData = [
                'tanggal_wawancara' => null,
                'mode' => null,
                'link_online' => null,
                'lokasi_offline' => null,
                'status_santri' => 'menunggu'
            ];

            $santri->update($updateData);
            DB::commit();

            Log::info('Interview schedule cancelled successfully', [
                'santri_id' => $santri->id,
                'old_schedule' => $santri->getOriginal('tanggal_wawancara')
            ]);

            session()->flash('success', 'Jadwal wawancara berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to cancel interview schedule', [
                'santri_id' => $santriId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', 'Terjadi kesalahan saat membatalkan jadwal wawancara.');
        }
    }

    #[Computed]
    public function registrations()
    {
        return PendaftaranSantri::query()
            ->with('wali')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->searchAlamat, function ($query) {
                $query->whereHas('wali', function ($q) {
                    $q->where('alamat', 'like', '%' . $this->searchAlamat . '%');
                });
            })
            ->when($this->filters['status'], function ($query) {
                $query->where('status_santri', $this->filters['status']);
            })
            ->when($this->filters['tipe'], function ($query) {
                $query->where('tipe_pendaftaran', $this->filters['tipe']);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function statusSantriOptions()
    {
        return [
            'menunggu' => 'Menunggu',
            'wawancara' => 'Wawancara',
            'sedang_ujian' => 'Sedang Ujian',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak'
        ];
    }

    #[Computed]
    public function tipeOptions()
    {
        return [
            'reguler' => 'Reguler',
            'olimpiade' => 'Olimpiade',
            'internasional' => 'Internasional',
            
        ];
    }

    public function render()
    {
        return view('livewire.admin.psb.show-registrations', [
            'registrations' => $this->registrations,
            'statusSantriOptions' => $this->statusSantriOptions(),
            'tipeOptions' => $this->tipeOptions(),
        ]);
    }
}