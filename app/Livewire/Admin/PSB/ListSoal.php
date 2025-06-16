<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\Ujian;
use App\Models\PSB\Soal;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ListSoal extends Component
{
    use WithPagination;

    public $ujian;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Form properties
    public $showForm = false;
    public $soalId = null;
    public $soalText = '';
    public $jenisSoal = 'essay';
    public $bobot = 100;

    protected $rules = [
        'soalText' => 'required|min:10',
        'jenisSoal' => 'required|in:essay,pilihan_ganda',
        'bobot' => 'required|numeric|min:1|max:100'
    ];

    protected $messages = [
        'soalText.required' => 'Soal tidak boleh kosong',
        'soalText.min' => 'Soal minimal 10 karakter',
        'jenisSoal.required' => 'Jenis soal harus dipilih',
        'jenisSoal.in' => 'Jenis soal tidak valid',
        'bobot.required' => 'Bobot soal harus diisi',
        'bobot.numeric' => 'Bobot harus berupa angka',
        'bobot.min' => 'Bobot minimal 1',
        'bobot.max' => 'Bobot maksimal 100'
    ];

    public function mount($ujianId)
    {
        $this->ujian = Ujian::findOrFail($ujianId);
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

    public function createSoal()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editSoal($soalId)
    {
        $soal = Soal::find($soalId);
        if ($soal) {
            $this->soalId = $soal->id;
            $this->soalText = $soal->soal;
            $this->jenisSoal = $soal->jenis_soal;
            $this->bobot = $soal->bobot;
            $this->showForm = true;
        }
    }

    public function deleteSoal($soalId)
    {
        try {
            DB::beginTransaction();
            
            $soal = Soal::find($soalId);
            if ($soal) {
                $soal->delete();
                session()->flash('message', 'Soal berhasil dihapus');
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan saat menghapus soal');
        }
    }

    public function saveSoal()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            if ($this->soalId) {
                $soal = Soal::find($this->soalId);
                $soal->update([
                    'soal' => $this->soalText,
                    'jenis_soal' => $this->jenisSoal,
                    'bobot' => $this->bobot
                ]);
                session()->flash('message', 'Soal berhasil diperbarui');
            } else {
                Soal::create([
                    'ujian_id' => $this->ujian->id,
                    'soal' => $this->soalText,
                    'jenis_soal' => $this->jenisSoal,
                    'bobot' => $this->bobot
                ]);
                session()->flash('message', 'Soal berhasil ditambahkan');
            }

            DB::commit();
            $this->resetForm();
            
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan soal');
        }
    }

    public function resetForm()
    {
        $this->soalId = null;
        $this->soalText = '';
        $this->jenisSoal = 'essay';
        $this->bobot = 100;
        $this->showForm = false;
    }

    public function render()
    {
        $soals = Soal::where('ujian_id', $this->ujian->id)
            ->when($this->search, function ($query) {
                $query->where('soal', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.psb.list-soal', [
            'soals' => $soals
        ]);
    }
} 
 
 
 
 
 