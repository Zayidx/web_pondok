<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PeriodeForm extends Form
{
    #[Validate('required|string|max:100')]
    public $nama_periode = '';

    #[Validate('required|date')]
    public $periode_mulai = '';

    #[Validate('required|date|after:periode_mulai')]
    public $periode_selesai = '';

    #[Validate('required|in:active,inactive')]
    public $status_periode = 'inactive';

    #[Validate('required|string|max:10')]
    public $tahun_ajaran = '';
}