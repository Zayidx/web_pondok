<?php

namespace App\Mail\PSB;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WawancaraScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Jadwal Wawancara PSB')
                    ->view('emails.psb.wawancara-scheduled');
    }
} 