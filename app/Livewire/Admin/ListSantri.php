<?php

namespace App\Livewire\Admin;

use App\Exports\SantriExport;
use App\Livewire\Forms\SantriForm;
use App\Livewire\Forms\WaliSantriForm;
use App\Models\admin\Semester;
use App\Models\admin\Angkatan;
use App\Models\Jenjang;
use App\Models\Santri;
use App\Models\Kamar;
use App\Models\Kelas;
use App\Models\OrangTuaSantri;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ListSantri extends Component
{
    use WithPagination;
    use WithFileUploads;

    #[Title('Halaman List Santri')]
    protected $paginationTheme = 'bootstrap';

    public SantriForm $santriForm;
    public WaliSantriForm $waliSantriForm;
    public $npsn = '70005521';

    #[Url(except: "")]
    public $perPage = 5;

    public $kelas, $kamar, $semester, $angkatan, $santri_id, $jenjang, $santriEditId, $formPage = 1;
    
    #[Url(except: '')]
    public $kelasFilter = '';
    #[Url(except: '')]
    public $jenjangFilter = '';
    #[Url(except: '')]
    public $kamarFilter = '';
    #[Url(except: '')]
    public $jenisKelaminFilter = '';
    
    public $user;

    #[Url(except: '', as: 'q-santri')]
    public $search = '';

    #[Validate('nullable|image|mimes:jpeg,png,jpg|max:4084')]
    public $foto;

    public $sortField = 'nama';
    public $sortDirection = 'asc';

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function mount()
    {
        if (request()->has('wali')) {
            $this->edit(request()->wali);
            $this->dispatch('showModal');
        }

        $this->kelas = Kelas::all();
        $this->kamar = Kamar::all();
        $this->semester = Semester::all();
        $this->angkatan = Angkatan::all();
        $this->jenjang = Jenjang::all();
    }

    public function prevForm()
    {
        $this->formPage--;
    }
    public function nextForm()
    {
        $this->formPage++;
    }
    public function create()
    {
        $this->santriForm->reset();
        $this->waliSantriForm->reset();
        $this->santriEditId = null;
    }

    public function createStore()
    {
        $this->santriForm->validate();
        $this->waliSantriForm->validate();
        $this->validate();

        $imgUrl = null;
        if ($this->foto) {
            $originalFileName = time() . "-" . $this->foto->hashName();
            $imgUrl = $this->foto->storeAs('images/santri', $originalFileName, 'public');
        }

        $data = $this->santriForm->all();
        $data['foto'] = $imgUrl;
        $santri = Santri::create($data);

        $waliSantriData = $this->waliSantriForm->all();
        $waliSantriData['santri_id'] = $santri->id;
        OrangTuaSantri::create($waliSantriData);

        User::create([
            'roles_id' => 6,
            'email' => $this->santriForm->nisn,
            'name' => $this->santriForm->nama,
            'password' => Hash::make($this->santriForm->nisn),
        ]);

        return to_route('admin.master-santri.santri')->with(['message' => "Success created " . $this->santriForm->nama . " !"]);
    }

    public function edit($santriId)
    {
        $this->santriEditId = $santriId;
        $santriData = Santri::findOrFail($santriId);
        $waliData = OrangTuaSantri::where('santri_id', $santriId)->firstOrNew();

        $this->user = User::where('email', $santriData->nisn)->first();

        $this->santriForm->fill($santriData->toArray());
        $this->waliSantriForm->fill($waliData->toArray());
        $this->foto = $santriData->foto;
    }

    public function editStore()
    {
        $this->santriForm->validate();
        $this->waliSantriForm->validate();
        $this->validate();

        $santri = Santri::findOrFail($this->santriEditId);
        $santriData = $this->santriForm->all();

        if ($this->foto && is_object($this->foto)) {
            if ($santri->foto && Storage::disk('public')->exists($santri->foto)) {
                Storage::disk('public')->delete($santri->foto);
            }
            $fileName = time() . '-' . $this->foto->hashName();
            $imgUrl = $this->foto->storeAs('images/santri', $fileName, 'public');
            $santriData['foto'] = $imgUrl;
        } else {
            $santriData['foto'] = $santri->foto;
        }

        $santri->update($santriData);
        OrangTuaSantri::updateOrCreate(
            ['santri_id' => $this->santriEditId],
            $this->waliSantriForm->all()
        );
        
        if($this->user) {
            $this->user->update([
                'email' => $this->santriForm->nisn,
                'name' => $this->santriForm->nama,
                'password' => Hash::make($this->santriForm->nisn),
            ]);
        }

        return to_route('admin.master-santri.santri')->with(['message' => "Success updated " . $santri->nama . " !"]);
    }

    #[On('delete')]
    public function delete($santriId)
    {
        $santri = Santri::find($santriId);
        if ($santri) {
            $santri->delete();
        }
    }

    public function export()
    {
        return Excel::download(new SantriExport, 'santri.xlsx');
    }

    public function render()
    {
        $query = Santri::with(['kamar', 'kelas.jenjang', 'orangTuaSantri']) 
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%');
            })
            ->when($this->kelasFilter, function ($query) {
                $query->whereHas('kelas', fn($q) => $q->where('nama', $this->kelasFilter));
            })
            ->when($this->jenjangFilter, function ($query) {
                $query->whereHas('kelas.jenjang', fn($q) => $q->where('nama', $this->jenjangFilter));
            })
            ->when($this->kamarFilter, function ($query) {
                $query->whereHas('kamar', fn($q) => $q->where('nama', $this->kamarFilter));
            })
            ->when($this->jenisKelaminFilter, function ($query) {
                $query->where('jenis_kelamin', $this->jenisKelaminFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $santris = $query->paginate($this->perPage);

        return view('livewire.admin.list-santri', [
            'santris' => $santris,
        ]);
    }
}