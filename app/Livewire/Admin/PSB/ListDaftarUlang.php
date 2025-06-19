<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use App\Models\Santri;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

#[Title('Dashboard Daftar Ulang')]
class ListDaftarUlang extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filters = [
        'tipe' => '',
        'status' => '',
        'urutan' => 'terbaru',
    ];

    public bool $showDetailModal = false;
    public bool $showProofModal = false;
    public ?PendaftaranSantri $selectedRegistration;
    public ?string $proofImageUrl = null;
    
    public bool $showEditModal = false;
    public $registrationIdBeingEdited = null;
    public $editForm = [
        'bank_pengirim' => '',
        'nama_pengirim' => '',
        'tanggal_pembayaran' => '',
    ];
    public $newProofImage;

    protected $paginationTheme = 'bootstrap';

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
    
    public function showDetail($id)
    {
        $this->selectedRegistration = PendaftaranSantri::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedRegistration = null;
    }

    public function viewPaymentProof($id)
    {
        $registration = PendaftaranSantri::findOrFail($id);
        if ($registration->bukti_pembayaran) {
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

    public function editRegistration($id)
    {
        $registration = PendaftaranSantri::findOrFail($id);
        $this->selectedRegistration = $registration;
        $this->registrationIdBeingEdited = $id;

        $this->editForm['bank_pengirim'] = $registration->bank_pengirim;
        $this->editForm['nama_pengirim'] = $registration->nama_pengirim;
        $this->editForm['tanggal_pembayaran'] = optional($registration->tanggal_pembayaran)->format('Y-m-d');
        
        $this->reset('newProofImage');
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset('selectedRegistration', 'registrationIdBeingEdited', 'editForm', 'newProofImage');
    }

    public function updateRegistration()
    {
        $validatedData = $this->validate([
            'editForm.bank_pengirim' => 'required|string|max:255',
            'editForm.nama_pengirim' => 'required|string|max:255',
            'editForm.tanggal_pembayaran' => 'required|date',
            'newProofImage' => 'nullable|image|max:2048'
        ]);

        $registration = PendaftaranSantri::findOrFail($this->registrationIdBeingEdited);

        $updateData = $validatedData['editForm'];

        if ($this->newProofImage) {
            if ($registration->bukti_pembayaran) {
                Storage::disk('public')->delete($registration->bukti_pembayaran);
            }
            $path = $this->newProofImage->store('bukti-pembayaran', 'public');
            $updateData['bukti_pembayaran'] = $path;
        }

        $registration->update($updateData);

        session()->flash('success', 'Data untuk ' . $registration->nama_lengkap . ' berhasil diperbarui.');
        $this->closeEditModal();
    }

    public function verifyRegistration($id)
    {
        Log::info('Memulai proses verifikasi pendaftaran ID: ' . $id);
        $registration = PendaftaranSantri::findOrFail($id);

        $existingSantri = Santri::where('nisn', $registration->nisn)->first();

        if (!$existingSantri) {
            Log::info('Santri dengan NISN ' . $registration->nisn . ' belum ada di tabel santris. Mencoba membuat entri baru.');

            $santriData = [
                'nama'                   => $registration->nama_lengkap,
                'nisn'                   => $registration->nisn,
                'tempat_lahir'           => $registration->tempat_lahir,
                'tanggal_lahir'          => $registration->tanggal_lahir,
                'jenis_kelamin'          => $registration->jenis_kelamin,
                'agama'                  => $registration->agama,
                'email'                  => $registration->email ?? null,
                'no_whatsapp'            => $registration->no_whatsapp ?? null,
                'asal_sekolah'           => $registration->asal_sekolah,
                'tahun_lulus'            => $registration->tahun_lulus,
                'password'               => Hash::make($registration->nisn),
                'status_santri'          => $registration->tipe_pendaftaran,
                'status_kesantrian'      => 'aktif',
                'periode_id'             => $registration->periode_id,

                'foto'                   => null,
                'nism'                   => null,
                'kewarganegaraan'        => 'WNI',
                'nik'                    => null,
                'jumlah_saudara_kandung' => null,
                'anak_ke'                => null,
                'hobi'                   => null,
                'aktivitas_pendidikan'   => 'aktif',
                'npsn'                   => null,
                'no_kip'                 => null,
                'no_kk'                  => null,
                'nama_kepala_keluarga'   => $registration->wali->nama_lengkap ?? null,
                'riwayat_penyakit'       => 'sehat',
                
                'kelas_id'               => null,
                'kamar_id'               => null,
                'semester_id'            => null,
                'angkatan_id'            => null,
                'yang_membiayai_sekolah' => null,
            ];

            try {
                Santri::create($santriData);
                session()->flash('info', 'Santri ' . $registration->nama_lengkap . ' berhasil ditambahkan ke daftar santri aktif.');
                Log::info('Santri baru berhasil dibuat di tabel santris.', ['nisn' => $registration->nisn, 'santri_id_baru' => Santri::where('nisn', $registration->nisn)->first()->id]);
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal menambahkan santri ke daftar aktif: ' . $e->getMessage());
                Log::error('Error adding santri to active list: ' . $e->getMessage(), [
                    'registration_id' => $id,
                    'santri_data_attempted' => $santriData,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return; 
            }
        } else {
            session()->flash('warning', 'Santri ' . $registration->nama_lengkap . ' sudah terdaftar di daftar santri aktif.');
            Log::info('Santri dengan NISN ' . $registration->nisn . ' sudah ada di tabel santris. Tidak membuat duplikasi.');
        }

        $registration->update([
            'status_pembayaran' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);
        Log::info('Status pembayaran pendaftaran santri ID ' . $id . ' diperbarui menjadi "verified".');

        session()->flash('success', 'Pendaftaran ulang ' . $registration->nama_lengkap . ' telah diterima.');
        $this->closeModal(); 
        Log::info('Proses verifikasi untuk pendaftaran ID ' . $id . ' selesai.');
    }

    public function rejectRegistration($id)
    {
        Log::info('Memulai proses penolakan pembayaran untuk pendaftaran ID: ' . $id);
        $registration = PendaftaranSantri::findOrFail($id);
        $registration->update([
            'status_pembayaran' => 'rejected',
        ]);
        Log::info('Status pembayaran pendaftaran santri ID ' . $id . ' diperbarui menjadi "rejected".');
        session()->flash('success', 'Bukti pembayaran ' . $registration->nama_lengkap . ' ditolak.');
        $this->closeModal(); 
        Log::info('Proses penolakan untuk pendaftaran ID ' . $id . ' selesai.');
    }

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
        $query = PendaftaranSantri::where('status_santri', 'diterima') 
            ->whereNotNull('status_pembayaran') 
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

        if ($this->filters['urutan'] === 'terbaru') {
            $query->orderBy('updated_at', 'desc'); 
        } elseif ($this->filters['urutan'] === 'terlama') {
            $query->orderBy('updated_at', 'asc');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return view('livewire.admin.psb.list-daftar-ulang', [
            'registrations' => $query->paginate($this->perPage),
        ]);
    }
}