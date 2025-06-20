<?php

namespace App\Livewire\PSB;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UjianForm extends Form
{
    #[Validate('required|string|max:100')]
    public $nama_ujian = '';

    #[Validate('required|string|max:100')]
    public $mata_pelajaran = '';

    #[Validate('required|exists:psb_periodes,id')]
    public $periode_id = '';

    #[Validate('required|date')]
    public $tanggal_ujian = '';

    #[Validate('required')]
    public $waktu_mulai = '';

    #[Validate('required|after:waktu_mulai')]
    public $waktu_selesai = '';

    #[Validate('required|in:draft,aktif,selesai')]
    public $status_ujian = 'draft';

    public function __construct($component, $propertyName)
    {
        parent::__construct($component, $propertyName);
    }
}