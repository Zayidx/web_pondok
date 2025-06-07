<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\HasilUjian as HasilUjianModel;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class HasilUjian extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedHasilUjian = null;
    public $showPublikasiModal = false;
    public $showRejectModal = false;
    public $alasanPenolakan = '';

    protected $rules = [
        'alasanPenolakan' => 'required|string|max:255'
    ];

    protected $messages = [
        'alasanPenolakan.required' => 'Alasan penolakan harus diisi',
        'alasanPenolakan.max' => 'Alasan penolakan maksimal 255 karakter'
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showPublikasiConfirmation($hasilUjianId)
    {
        $this->selectedHasilUjian = HasilUjianModel::findOrFail($hasilUjianId);
        $this->showPublikasiModal = true;
    }

    public function publikasiNilai()
    {
        if (!$this->selectedHasilUjian) {
            return;
        }

        try {
            DB::beginTransaction();

            // Update hasil ujian status
            $this->selectedHasilUjian->update([
                'status' => 'dipublikasi'
            ]);

            // Update santri status
            $this->selectedHasilUjian->santri->update([
                'status_santri' => 'menunggu_hasil'
            ]);

            DB::commit();

            $this->showPublikasiModal = false;
            $this->selectedHasilUjian = null;
            session()->flash('message', 'Nilai berhasil dipublikasikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat mempublikasi nilai.');
        }
    }

    public function terimaSantri($santriId)
    {
        try {
            DB::beginTransaction();

            $santri = PendaftaranSantri::findOrFail($santriId);
            $santri->update([
                'status_santri' => 'diterima'
            ]);

            DB::commit();
            session()->flash('message', 'Santri berhasil diterima.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memproses penerimaan santri.');
        }
    }

    public function showRejectConfirmation($santriId)
    {
        $this->selectedHasilUjian = HasilUjianModel::whereHas('santri', function($q) use ($santriId) {
            $q->where('id', $santriId);
        })->first();
        
        if ($this->selectedHasilUjian) {
            $this->showRejectModal = true;
        }
    }

    public function tolakSantri()
    {
        $this->validate();

        if (!$this->selectedHasilUjian) {
            return;
        }

        try {
            DB::beginTransaction();

            $this->selectedHasilUjian->santri->update([
                'status_santri' => 'ditolak',
                'alasan_penolakan' => $this->alasanPenolakan
            ]);

            DB::commit();

            $this->showRejectModal = false;
            $this->selectedHasilUjian = null;
            $this->alasanPenolakan = '';
            session()->flash('message', 'Santri telah ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memproses penolakan santri.');
        }
    }

    public function render()
    {
        $hasilUjians = HasilUjianModel::with(['santri', 'ujian'])
            ->whereHas('santri', function($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.psb.hasil-ujian', [
            'hasilUjians' => $hasilUjians
        ]);
    }
} 
 
 
 
 
 