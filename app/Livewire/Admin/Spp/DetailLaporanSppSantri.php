<?php

namespace App\Livewire\Admin\Spp;

use App\Models\Santri;
use App\Models\Spp\Pembayaran;
use Livewire\Attributes\Title;
use Livewire\Component;

class DetailLaporanSppSantri extends Component
{
    #[Title('Halaman Detail Spp Santri')]

    public $santriId;
    public $santri;
    public $filter = [
        'tahun' => '',
        'status' => ''
    ];

    public function mount($id)
    {
        $this->santriId = $id;
        // Memuat hanya field yang diperlukan
        $this->santri = Santri::select('id', 'nama', 'kelas_id', 'kamar_id')
            ->with([
                'kelas:id,nama',
                'kamar:id,nama'
            ])
            ->findOrFail($id);

        $this->filter['tahun'] = date('Y');
    }

    protected function getPembayaran()
    {
        return Pembayaran::with([
            'pembayaranTimeline:id,nama_bulan',
            'pembayaranTipe:id,nama'
        ])
            ->where('santri_id', $this->santriId)
            ->when($this->filter['tahun'], function ($query) {
                $query->whereYear('created_at', $this->filter['tahun']);
            })
            ->when($this->filter['status'], function ($query) {
                $query->where('status', $this->filter['status']);
            })
            ->orderBy('created_at')
            ->get();
    }

    protected function getTahunList()
    {
        return Pembayaran::query()
            ->where('santri_id', $this->santriId)
            ->selectRaw('DISTINCT YEAR(created_at) as tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');
    }

    public function render()
    {
        $pembayaran = $this->getPembayaran();

        return view('livewire.admin.spp.detail-laporan-spp-santri', [
            'pembayaran' => $pembayaran,
            'tahunList' => $this->getTahunList(),
            'total_pembayaran' => $pembayaran->count(),
            'total_nominal' => $pembayaran->sum('nominal'),
        ]);
    }
}
