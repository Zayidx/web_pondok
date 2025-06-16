<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\Ujian;
use App\Models\PSB\HasilUjian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ujian')]
#[Title('Konfirmasi Selesai Ujian')]
class KonfirmasiSelesai extends Component
{
    public $ujian;
    public $hasilUjian;
    public $santri;
    public $soalDijawab = 0;
    public $jumlahSoal = 0;
    public $belumDijawab = [];

    public function mount($ujianId)
    {
        $this->santri = Auth::guard('santri')->user();
        if (!$this->santri) {
            return redirect()->route('login-ppdb-santri');
        }

        $this->ujian = Ujian::findOrFail($ujianId);
        $this->hasilUjian = HasilUjian::where([
            'santri_id' => $this->santri->id,
            'ujian_id' => $this->ujian->id,
        ])->firstOrFail();

        $this->jumlahSoal = $this->ujian->soals()->count();
        
        // Hitung soal yang sudah dijawab
        $jawabanUjians = $this->hasilUjian->jawabanUjians;
        $this->soalDijawab = $jawabanUjians->count();

        // Cari soal yang belum dijawab
        $soalIds = $jawabanUjians->pluck('soal_id')->toArray();
        $ujian = $this->ujian; // Store in local variable
        $this->belumDijawab = $this->ujian->soals()
            ->whereNotIn('id', $soalIds)
            ->get()
            ->map(function($soal, $index) use ($ujian) {
                $nomorSoal = $ujian->soals->search(function($item) use ($soal) {
                    return $item->id === $soal->id;
                }) + 1;
                return $nomorSoal;
            })
            ->toArray();
    }

    public function kembaliKeUjian()
    {
        return redirect()->route('santri.mulai-ujian', ['ujianId' => $this->ujian->id]);
    }

    public function selesaiUjian()
    {
        try {
            DB::beginTransaction();

        // Update hasil ujian
        $this->hasilUjian->update([
            'waktu_selesai' => now(),
            'status' => 'selesai'
        ]);

            // Check if all exams are completed
            $totalActiveExams = Ujian::where('status_ujian', 'aktif')->count();
            $completedExams = HasilUjian::where('santri_id', $this->santri->id)
                ->where('status', 'selesai')
                ->count();

            // If all exams are completed, update status to waiting for results
            if ($completedExams >= $totalActiveExams) {
                $this->santri->update(['status_santri' => 'menunggu_hasil']);
            } else {
        $this->santri->update(['status_santri' => 'menunggu']);
            }

            DB::commit();
            return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat mengumpulkan ujian.');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.santri-p-p-d-b.konfirmasi-selesai');
    }
} 