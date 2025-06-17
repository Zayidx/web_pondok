<?php
namespace App\Livewire\Admin\ESantri\GuruUmum;

use App\Livewire\Forms\JadwalPelajaranForm;
use App\Models\ESantri\JadwalPelajaran as ModelsJadwalPelajaran;
use App\Models\ESantri\KategoriPelajaran;
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

    public $kelasList, $kategoriPelajaranList,$jenjang_id;
    
    public $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
    
    public function mount()
    {
        $this->kelasList = $this->kelasList();
        $this->kategoriPelajaranList = $this->kategoriPelajaranList();
    }

    #[Computed()]
    public function listJadwalPelajaran()
    {
        return ModelsJadwalPelajaran::where('role_guru', 'umum')->with(['kelas', 'kategoriPelajaran'])
            ->paginate(10);
    }

    #[Computed()]
    public function kelasList()
    {
        return Kelas::all();
    }

    #[Computed()]
    public function kategoriPelajaranList()
    {
        return KategoriPelajaran::where('role_guru', 'umum')->get();
    }

    public function create()
    {
        $this->jadwalPelajaranId = null;
        $this->jadwalPelajaranForm->reset();
        session()->flash('message', 'Jadwal Pelajaran berhasil ditambahkan!');
            $this->dispatch('close-modal');
    }

    public function createJadwalPelajaran()
    {
        try {
            $this->jadwalPelajaranForm->role_guru = 'umum';

            $this->jadwalPelajaranForm->validate();
            $kelas = Kelas::find($this->jadwalPelajaranForm->kelas_id);
// Memastikan jenjang_id dari kelas yang dipilih ikut disimpan
if ($kelas) {
    $this->jadwalPelajaranForm->jenjang_id = $kelas->jenjang_id;}
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
    }

    public function updateJadwalPelajaran()
    {
        try {
            $this->jadwalPelajaranForm->role_guru = 'umum';

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
        $this->detailJadwalPelajaranList = ModelsJadwalPelajaran::where('role_guru', 'umum')->with(['kelas', 'kategoriPelajaran'])
            ->findOrFail($id);  
    }

    public function render()
    {
        return view('livewire.admin.e-santri.guru-umum.jadwal-pelajaran', [
            'listJadwalPelajaran' => $this->listJadwalPelajaran(),
        ]);
    }
}