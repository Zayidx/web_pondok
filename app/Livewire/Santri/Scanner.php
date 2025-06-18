<?php

namespace App\Livewire\Santri;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Scanner extends Component
{
    #[Layout('components.layouts.app-mobile')]
    /**
     * Method ini hanya bertugas untuk merender view yang akan berisi kamera.
     * Tidak ada logika kompleks yang dibutuhkan di sini.
     */
    public function render()
    {
        // Komentar: Pastikan Anda menggunakan layout yang sesuai untuk halaman santri.
        return view('livewire.santri.scanner');
    }
}
