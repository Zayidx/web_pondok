<?php

namespace App\Livewire\Admin\PSB;

use App\Models\admin\Admin;
use Livewire\Component;
use App\Models\PSB\BiayaDaftarUlang;
use App\Models\PSB\PengaturanDaftarUlang;
use App\Models\PSB\PsbPeriode; // Diubah dari App\Models\PSB\Periode ke App\Models\PSB\PsbPeriode

class DaftarUlangSettings extends Component
{
    // Properties for biaya
    public $nama_biaya;
    public $nominal; // Ini akan dipetakan ke kolom 'jumlah' di BiayaDaftarUlang
    public $keterangan;
    public $is_active = true;
    public $biaya_id;
    public $is_editing = false;

    // Properties for pengaturan
    public $nama_bank;
    public $nomor_rekening;
    public $atas_nama;
    public $catatan_transfer;

    protected $rules = [
        'nama_biaya' => 'required|min:3',
        'nominal' => 'required|numeric|min:0',
        'keterangan' => 'nullable',
        'nama_bank' => 'required',
        'nomor_rekening' => 'required',
        'atas_nama' => 'required',
        'catatan_transfer' => 'nullable'
    ];

    public function mount()
    {
        $this->loadPengaturan();
    }

    public function loadPengaturan()
    {
        $pengaturan = PengaturanDaftarUlang::first();
        if ($pengaturan) {
            $this->nama_bank = $pengaturan->nama_bank;
            $this->nomor_rekening = $pengaturan->nomor_rekening;
            $this->atas_nama = $pengaturan->atas_nama;
            $this->catatan_transfer = $pengaturan->catatan_transfer;
        }
    }

    public function savePengaturan()
    {
        $this->validate([
            'nama_bank' => 'required',
            'nomor_rekening' => 'required',
            'atas_nama' => 'required',
            'catatan_transfer' => 'nullable'
        ]);

        PengaturanDaftarUlang::updateOrCreate(
            ['id' => 1],
            [
                'nama_bank' => $this->nama_bank,
                'nomor_rekening' => $this->nomor_rekening,
                'atas_nama' => $this->atas_nama,
                'catatan_transfer' => $this->catatan_transfer,
                'is_active' => true,
            ]
        );

        session()->flash('message', 'Pengaturan berhasil disimpan!');
    }

    public function saveBiaya()
    {
        $this->validate([
            'nama_biaya' => 'required|min:3',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable'
        ]);

        $data = [
            'nama_biaya' => $this->nama_biaya,
            'jumlah' => $this->nominal, // Memetakan nominal ke jumlah (sesuai DB)
            'keterangan' => $this->keterangan,
            'is_active' => $this->is_active,
            'tahun_ajaran' => '2025/2026', // Pastikan kolom ini diisi
        ];

        if ($this->is_editing) {
            $biaya = BiayaDaftarUlang::find($this->biaya_id);
            $biaya->update($data);
        } else {
            BiayaDaftarUlang::create($data);
        }

        $this->reset(['nama_biaya', 'nominal', 'keterangan', 'is_active', 'biaya_id', 'is_editing']);
        session()->flash('message', 'Biaya berhasil disimpan!');
    }

    public function editBiaya($id)
    {
        $biaya = BiayaDaftarUlang::find($id);
        $this->biaya_id = $biaya->id;
        $this->nama_biaya = $biaya->nama_biaya;
        $this->nominal = $biaya->jumlah; // Memuat dari kolom 'jumlah' (sesuai DB)
        $this->keterangan = $biaya->keterangan;
        $this->is_active = $biaya->is_active;
        $this->is_editing = true;
    }

    public function deleteBiaya($id)
    {
        BiayaDaftarUlang::find($id)->delete();
        session()->flash('message', 'Biaya berhasil dihapus!');
    }

    public function cancelEdit()
    {
        $this->reset(['nama_biaya', 'nominal', 'keterangan', 'is_active', 'biaya_id', 'is_editing']);
    }

    public function render()
    {
        return view('livewire.admin.psb.daftar-ulang-settings', [
            'biayas' => BiayaDaftarUlang::all(),
            // getTotalBiaya() sekarang akan menjumlahkan kolom 'jumlah'
            'total_biaya' => BiayaDaftarUlang::getTotalBiaya(),
            // Menggunakan PsbPeriode sesuai dengan nama tabel psb_periodes
            'periode_daftar_ulang' => \App\Models\PSB\Periode::where('tipe_periode', 'daftar_ulang')
                ->where('status_periode', 'active')
                ->first()
        ]);
    }
}
