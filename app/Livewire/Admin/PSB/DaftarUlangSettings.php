<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\BiayaDaftarUlang;
use App\Models\PSB\PengaturanDaftarUlang;
use App\Models\PSB\Periode;
use Livewire\Attributes\Title;

class DaftarUlangSettings extends Component
{
    // Properties for biaya
    #[Title('Pengaturan Daftar Ulang')]
    public $nama_biaya;
    public $nominal;
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
            ['id' => 1], // Selalu update record pertama
            [
                'nama_bank' => $this->nama_bank,
                'nomor_rekening' => $this->nomor_rekening,
                'atas_nama' => $this->atas_nama,
                'catatan_transfer' => $this->catatan_transfer,
                'is_active' => true,
            ]
        );

        session()->flash('message', 'Pengaturan rekening berhasil disimpan!');
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
            'jumlah' => $this->nominal, // Memetakan 'nominal' dari form ke kolom 'jumlah'
            'keterangan' => $this->keterangan,
            'is_active' => $this->is_active,
            'tahun_ajaran' => '2025/2026', // Ini bisa dibuat dinamis nanti
        ];

        if ($this->is_editing) {
            $biaya = BiayaDaftarUlang::find($this->biaya_id);
            if ($biaya) {
                $biaya->update($data);
                session()->flash('message', 'Biaya berhasil diperbarui!');
            }
        } else {
            BiayaDaftarUlang::create($data);
            session()->flash('message', 'Biaya berhasil ditambahkan!');
        }

        $this->cancelEdit();
    }

    public function editBiaya($id)
    {
        $biaya = BiayaDaftarUlang::find($id);
        if ($biaya) {
            $this->biaya_id = $biaya->id;
            $this->nama_biaya = $biaya->nama_biaya;
            $this->nominal = $biaya->jumlah; // Memetakan 'jumlah' dari DB ke 'nominal' di form
            $this->keterangan = $biaya->keterangan;
            $this->is_active = $biaya->is_active;
            $this->is_editing = true;
        }
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
            'total_biaya' => BiayaDaftarUlang::getTotalBiaya(),
            'periode_daftar_ulang' => Periode::where('tipe_periode', 'daftar_ulang')
                ->where('status_periode', 'active')
                ->first()
        ]);
    }
}