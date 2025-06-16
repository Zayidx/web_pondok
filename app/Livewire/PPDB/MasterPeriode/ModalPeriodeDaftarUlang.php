namespace App\Livewire\PPDB\MasterPeriode;

use LivewireUI\Modal\ModalComponent;
use App\Models\PSB\PeriodeDaftarUlang;

class ModalPeriodeDaftarUlang extends ModalComponent
{
    public $periodeId;
    public $nama_periode;
    public $tahun_ajaran;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $is_active = true;

    protected $rules = [
        'nama_periode' => 'required|string|max:255',
        'tahun_ajaran' => 'required|string|max:255',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'is_active' => 'boolean'
    ];

    public function mount($periodeId = null)
    {
        if ($periodeId) {
            $periode = PeriodeDaftarUlang::findOrFail($periodeId);
            $this->periodeId = $periode->id;
            $this->nama_periode = $periode->nama_periode;
            $this->tahun_ajaran = $periode->tahun_ajaran;
            $this->tanggal_mulai = $periode->tanggal_mulai->format('Y-m-d');
            $this->tanggal_selesai = $periode->tanggal_selesai->format('Y-m-d');
            $this->is_active = $periode->is_active;
        }
    }

    public function save()
    {
        $this->validate();

        PeriodeDaftarUlang::updateOrCreate(
            ['id' => $this->periodeId],
            [
                'nama_periode' => $this->nama_periode,
                'tahun_ajaran' => $this->tahun_ajaran,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'is_active' => $this->is_active
            ]
        );

        $this->closeModalWithEvents([
            'periodeDaftarUlangUpdated' => true
        ]);
    }

    public function render()
    {
        return view('livewire.ppdb.master-periode.modal-periode-daftar-ulang');
    }
} 