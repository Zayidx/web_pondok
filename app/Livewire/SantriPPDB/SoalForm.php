<?php

namespace App\Livewire\SantriPPDB;

use App\Models\PSB\Soal;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SoalForm extends Form
{
    public function __construct($component, $propertyName)
    {
        parent::__construct($component, $propertyName);
    }

    #[Validate('required|exists:ujians,id')]
    public $ujian_id;

    #[Validate('required|in:pg,essay')]
    public $tipe_soal = 'pg';

    #[Validate('required|string')]
    public $pertanyaan = '';

    #[Validate('required_if:tipe_soal,pg|array|min:2')]
    public $opsi = [
        ['teks' => '', 'bobot' => 0],
        ['teks' => '', 'bobot' => 0],
    ];

    #[Validate('required_if:tipe_soal,pg|integer|min:0')]
    public $kunci_jawaban = 0;

    #[Validate('required|integer|min:1')]
    public $bobot_nilai = 1;

    public function addOption()
    {
        $this->opsi[] = ['teks' => '', 'bobot' => 0];
    }

    public function removeOption($index)
    {
        if (count($this->opsi) > 2) {
            unset($this->opsi[$index]);
            $this->opsi = array_values($this->opsi);
            if ($this->kunci_jawaban >= $index) {
                $this->kunci_jawaban = max(0, min(count($this->opsi) - 1, (int)$this->kunci_jawaban));
            }
        }
    }

    public function rules()
    {
        return [
            'ujian_id' => 'required|exists:ujians,id',
            'tipe_soal' => 'required|in:' . implode(',', array_keys(Soal::getTipeOptions())),
            'pertanyaan' => 'required|string',
            'opsi' => 'required_if:tipe_soal,' . Soal::TIPE_PG . '|array|min:2',
            'opsi.*.teks' => 'required_if:tipe_soal,' . Soal::TIPE_PG . '|string',
            'opsi.*.bobot' => 'required_if:tipe_soal,' . Soal::TIPE_PG . '|integer|min:0',
            'kunci_jawaban' => 'required_if:tipe_soal,' . Soal::TIPE_PG . '|integer|min:0',
        ];
    }
}