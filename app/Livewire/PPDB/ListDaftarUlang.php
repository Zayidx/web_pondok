<?php

namespace App\Livewire\PPDB;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PSB\PendaftaranUlang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ListDaftarUlang extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $tahunAjaran = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'tahunAjaran' => ['except' => '']
    ];

    public function verifikasiPendaftaran($id)
    {
        $pendaftaran = PendaftaranUlang::findOrFail($id);
        $pendaftaran->update([
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now()
        ]);

        // Update status siswa
        $pendaftaran->siswa->update([
            'status' => 'selesai_daftar_ulang'
        ]);

        $this->dispatch('showToast', [
            'type' => 'success',
            'message' => 'Pendaftaran berhasil diverifikasi'
        ]);
    }

    public function tolakPendaftaran($id, $catatan = '')
    {
        $pendaftaran = PendaftaranUlang::findOrFail($id);
        $pendaftaran->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'catatan_verifikasi' => $catatan
        ]);

        $this->dispatch('showToast', [
            'type' => 'success',
            'message' => 'Pendaftaran ditolak'
        ]);
    }

    public function render()
    {
        $pendaftaran = PendaftaranUlang::with(['siswa', 'verifikator'])
            ->whereHas('siswa', function($query) {
                $query->where('status', 'daftar_ulang');
            })
            ->when($this->search, function($query) {
                $query->whereHas('siswa', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.ppdb.list-daftar-ulang', [
            'pendaftaran' => $pendaftaran
        ]);
    }
} 