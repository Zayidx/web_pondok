<?php
// app/Livewire/TestButton.php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log; // Baris ini ditambahkan!
class TestButton extends Component
{
    public function testClick()
    {
        // Ini akan muncul di log Laravel jika berhasil
        Log::info('TestButton clicked!');
        // Ini akan langsung mematikan halaman dengan pesan
        dd('Test button was clicked via Livewire!');
    }

    public function render()
    {
        return view('livewire.test-button');
    }
}