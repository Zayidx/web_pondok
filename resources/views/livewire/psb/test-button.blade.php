{{-- resources/views/livewire/test-button.blade.php --}}
<div>
    <h1>Ini adalah tombol Livewire Test!</h1>
    <button wire:click="testClick" style="padding: 10px 20px; background-color: green; color: white; border: none; cursor: pointer;">
        KLIK SAYA (TEST LIVEWIRE)
    </button>
    <p>Periksa konsol browser dan log Laravel setelah klik.</p>
</div>