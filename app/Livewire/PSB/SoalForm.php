<?php

namespace App\Livewire\PSB;

use App\Models\PSB\Soal;
use Livewire\Attributes\Validate;
use Livewire\Form;

/**
 * SoalForm Class
 * 
 * Form untuk mengelola input soal ujian, baik pilihan ganda maupun essay.
 * Form ini menangani:
 * - Input pertanyaan
 * - Pemilihan tipe soal (PG/Essay)
 * - Manajemen opsi jawaban untuk soal PG
 * - Validasi input
 */
class SoalForm extends Form
{
    /**
     * Constructor untuk form
     * 
     * @param Component $component Komponen Livewire yang menggunakan form ini
     * @param string $propertyName Nama property di komponen
     */
    public function __construct($component, $propertyName)
    {
        parent::__construct($component, $propertyName);
    }

    /**
     * ID ujian yang soalnya sedang dikelola
     * Harus merujuk ke ujian yang valid di database
     */
    #[Validate('required|exists:ujians,id')]
    public $ujian_id;

    /**
     * Tipe soal: 'pg' untuk pilihan ganda, 'essay' untuk essay
     * Default: 'pg'
     */
    #[Validate('required|in:pg,essay')]
    public $tipe_soal = 'pg';

    /**
     * Teks pertanyaan untuk soal
     * Tidak boleh kosong
     */
    #[Validate('required|string')]
    public $pertanyaan = '';

    /**
     * Array opsi jawaban untuk soal pilihan ganda
     * Minimal harus ada 2 opsi
     * Setiap opsi memiliki:
     * - teks: isi opsi jawaban
     * - bobot: nilai jika memilih opsi ini (tertinggi = jawaban benar)
     */
    #[Validate('required_if:tipe_soal,pg|array|min:2')]
    public $opsi = [
        ['teks' => '', 'bobot' => 0],
        ['teks' => '', 'bobot' => 0],
    ];

    /**
     * Index opsi yang merupakan jawaban benar
     * Hanya digunakan untuk soal pilihan ganda
     * Ditentukan otomatis berdasarkan bobot tertinggi
     */
    #[Validate('required_if:tipe_soal,pg|integer|min:0')]
    public $kunci_jawaban = 0;

    /**
     * Menambah opsi baru ke soal pilihan ganda
     * Opsi baru akan ditambahkan dengan teks kosong dan bobot 0
     */
    public function addOption()
    {
        $this->opsi[] = ['teks' => '', 'bobot' => 0];
    }

    /**
     * Menghapus opsi dari soal pilihan ganda
     * 
     * @param int $index Index opsi yang akan dihapus
     * 
     * Catatan:
     * - Minimal harus tetap ada 2 opsi
     * - Jika menghapus opsi yang merupakan kunci jawaban,
     *   kunci jawaban akan diupdate ke opsi valid terdekat
     */
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

    /**
     * Aturan validasi untuk form
     * 
     * @return array Array aturan validasi Laravel
     */
    public function rules()
    {
        return [
            'ujian_id' => 'required|exists:ujians,id',
            'tipe_soal' => 'required|in:pg,essay',
            'pertanyaan' => 'required|string',
            'opsi' => 'required_if:tipe_soal,pg|array|min:2',
            'opsi.*.teks' => 'required_if:tipe_soal,pg|string',
            'opsi.*.bobot' => 'required_if:tipe_soal,pg|integer|min:0',
            'kunci_jawaban' => 'required_if:tipe_soal,pg|integer|min:0',
        ];
    }
}