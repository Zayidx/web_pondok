<?php

   namespace App\Livewire\PSB;

   use Livewire\Component;
   use App\Models\PSB\PendaftaranSantri; // Adjust model name based on your setup

   class ShowRegistrations extends Component
   {
       public $registrations;

       public function mount()
       {
           $this->registrations = PendaftaranSantri::all(); // Fetch all records
       }

       public function render()
       {
           return view('livewire.p-s-b.show-registrations');
       }
   }