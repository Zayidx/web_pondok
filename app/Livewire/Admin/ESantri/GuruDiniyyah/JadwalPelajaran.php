<?php

namespace App\Livewire\Admin\ESantri\GuruDiniyyah;

use App\Livewire\Forms\JadwalPelajaranForm;
use App\Models\ESantri\JadwalPelajaran as ModelsJadwalPelajaran;
use App\Models\ESantri\KategoriPelajaran;
use App\Models\Jenjang;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class JadwalPelajaran extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Title('Halaman Jadwal Pelajaran')]
    public JadwalPelajaranForm $jadwalPelajaranForm;

    public $jadwalPelajaranId;
    public $detailJadwalPelajaranList;

    public $kategoriPelajaranList;

    public $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function mount()
    {
        $this->kategoriPelajaranList = $this->kategoriPelajaranList();
    }

    #[Computed()]
    public function listJadwalPelajaran()
    {
        return ModelsJadwalPelajaran::where('role_guru', 'diniyyah')->with(['kelas', 'kategoriPelajaran'])
            ->paginate(10);
    }

    #[Computed()]
    public function kelasList()
    {
        return Kelas::with('jenjang')->when($this->jadwalPelajaranForm->jenjang_id)->where('jenjang_id', 'LIKE', "%{$this->jadwalPelajaranForm->jenjang_id}%")->get();
    }

    #[Computed()]
    public function jenjangList()
    {
        return Jenjang::all();
    }

    #[Computed()]
    public function kategoriPelajaranList()
    {
        return KategoriPelajaran::where('role_guru', 'diniyyah')->get();
    }

    public function create()
    {
        $this->jadwalPelajaranId = null;
        $this->jadwalPelajaranForm->reset();
    }

    public function createJadwalPelajaran()
    {
        try {
            $this->jadwalPelajaranForm->role_guru = 'diniyyah';

            $this->jadwalPelajaranForm->validate();

            ModelsJadwalPelajaran::create($this->jadwalPelajaranForm->all());

            session()->flash('success', 'Jadwal Pelajaran baru berhasil dibuat!');
            $this->dispatch('close-modal');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->jadwalPelajaranId = $id;
        $jadwalEdit = ModelsJadwalPelajaran::findOrFail($id);
        $this->jadwalPelajaranForm->fill($jadwalEdit);

        // dd($jadwalEdit);
    }

    public function updateJadwalPelajaran()
    {
        try {
            $this->jadwalPelajaranForm->role_guru = 'diniyyah';

            $this->jadwalPelajaranForm->validate();

            ModelsJadwalPelajaran::findOrFail($this->jadwalPelajaranId)
                ->update($this->jadwalPelajaranForm->all());

            session()->flash('success', 'Jadwal Pelajaran berhasil diupdate!');
            $this->dispatch('close-modal');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteJadwalPelajaran($id)
    {
        $jadwal = ModelsJadwalPelajaran::findOrFail($id);
        session()->flash('success', 'Berhasil hapus jadwal pelajaran');
        $jadwal->delete();
    }

    public function detailJadwalPelajaran($id)
    {
        $this->detailJadwalPelajaranList = ModelsJadwalPelajaran::where('role_guru', 'diniyyah')->with(['kelas', 'kategoriPelajaran'])
            ->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.e-santri.guru-diniyyah.jadwal-pelajaran', [
            'listJadwalPelajaran' => $this->listJadwalPelajaran(),
        ]);
    }
}
