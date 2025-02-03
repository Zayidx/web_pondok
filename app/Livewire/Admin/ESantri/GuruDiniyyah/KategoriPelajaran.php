<?php

namespace App\Livewire\Admin\ESantri\GuruDiniyyah;

use App\Livewire\Forms\KategoriPelajaranForm;
use App\Models\ESantri\KategoriPelajaran as ModelsKategoriPelajaran;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class KategoriPelajaran extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Title('Halaman Kategori Pelajaran')]
    public KategoriPelajaranForm $kategoriPelajaranForm;

    public $kategoriPelajaranId;
    public $detailKategoriPelajaran;

    #[Computed()]
    public function listKategoriPelajaran()
    {
        return ModelsKategoriPelajaran::where('role_guru', 'diniyyah')->paginate(10);
    }

    public function create()
    {
        $this->kategoriPelajaranId = null;
        $this->kategoriPelajaranForm->reset();
    }

    public function createKategoriPelajaran()
    {
        try {
            $this->kategoriPelajaranForm->role_guru = 'diniyyah';
            $this->kategoriPelajaranForm->validate();

            ModelsKategoriPelajaran::create($this->kategoriPelajaranForm->all());

            return to_route('e-santri-guru-diniyyah.kategori-pelajaran')->with('success', 'Kategori Pelajaran baru berhasil dibuat!');
            // $this->dispatch('closeModalCreateOrUpdate');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->kategoriPelajaranId = $id;
        $kategoriEdit = ModelsKategoriPelajaran::findOrFail($id);
        $this->kategoriPelajaranForm->fill($kategoriEdit);
    }

    public function updateKategoriPelajaran()
    {
        try {
            $this->kategoriPelajaranForm->role_guru = 'diniyyah';
            $this->kategoriPelajaranForm->validate();

            ModelsKategoriPelajaran::findOrFail($this->kategoriPelajaranId)
                ->update($this->kategoriPelajaranForm->all());

            return to_route('e-santri-guru-diniyyah.kategori-pelajaran')->with('success', 'Kategori Pelajaran baru berhasil diupdate!');
            // $this->dispatch('closeModalCreateOrUpdate');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteKategoriPelajaran($id)
    {
        try {
            $kategori = ModelsKategoriPelajaran::findOrFail($id);

            // // Cek apakah kategori sudah digunakan di jadwal pelajaran
            // if ($kategori->jadwalPelajaran()->exists()) {
            //     session()->flash('error', 'Kategori tidak bisa dihapus karena sudah digunakan di jadwal pelajaran!');
            //     return;
            // }
            $kategori->delete();

            return to_route('e-santri-guru-diniyyah.kategori-pelajaran')->with('success', 'Berhasil hapus kategori pelajaran: ' . $kategori->nama);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getDetailKategoriPelajaran($id)
    {
        $this->detailKategoriPelajaran = ModelsKategoriPelajaran::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.e-santri.guru-diniyyah.kategori-pelajaran', [
            'listKategoriPelajaran' => $this->listKategoriPelajaran(),
        ]);
    }
}
