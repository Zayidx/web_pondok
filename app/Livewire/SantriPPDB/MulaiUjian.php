<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\Ujian; // Import the Ujian model (ensure the namespace is correct: App\Models\PSB\Ujian).
use App\Models\PSB\HasilUjian; // Import the HasilUjian model.
use App\Models\PSB\JawabanUjian; // Import the JawabanUjian model.
use App\Models\PSB\PendaftaranSantri; // Import the PendaftaranSantri model.
use Illuminate\Support\Facades\Auth; // Import the Auth facade for authentication.
use Carbon\Carbon; // Import the Carbon class for date and time manipulation.
use Livewire\Attributes\Layout; // Import the Layout attribute for Blade layout.
use Livewire\Attributes\Title; // Import the Title attribute for page title.
use Livewire\Attributes\Computed; // Import the Computed attribute for computed properties.
use Illuminate\Support\Facades\DB; // Import the DB facade for database transactions.
use Illuminate\Support\Facades\Log; // Import the Log facade for logging.

/**
 * MulaiUjian Livewire Class.
 *
 * This component manages the display and logic for the student's exam page.
 * It handles exam initialization, saving answers, managing the countdown timer,
 * and processing exam submissions.
 */
class MulaiUjian extends Component
{
    #[Layout('components.layouts.ujian')] // Sets the Blade layout to be used.
    #[Title('Mulai Ujian')] // Sets the page title.

    public $ujian; // Public property to store the current exam object data.
    public $santri; // Public property to store the logged-in student object data.
    public $hasilUjian; // Public property to store the current HasilUjian record.
    public $jumlahSoal; // Public property to store the total number of questions in the exam.
    public $jawabanSiswa = []; // Associative array to store student answers (key: soal_id, value: answer).
    public $soalDijawab = 0; // Number of questions answered by the student.
    public $isFinished = false; // Status of whether the exam is finished or not.
    public $currentPage = 1; // The current question page being displayed.
    public $jawaban; // Temporary property for binding the current question's answer input.
    public $modalMessage = ''; // Message to be displayed in the confirmation modal.
    public $showModal = false; // Visibility status of the confirmation modal.
    public $sisaWaktuDetik; // Remaining exam time in seconds.

    /**
     * The mount function, executed when the component is initialized.
     * Retrieves student and exam data, and initializes exam results.
     *
     * @param int $ujianId The ID of the exam to start.
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function mount($ujianId)
    {
        // Get student data from 'santri' guard or from session if available.
        $this->santri = Auth::guard('santri')->user() ?? PendaftaranSantri::find(session('santri_id'));

        // If student is not found, log out and redirect to the login page.
        if (!$this->santri) {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        // Get exam data including the count of associated questions.
        $this->ujian = Ujian::withCount('soals')->findOrFail($ujianId);
        $this->jumlahSoal = $this->ujian->soals_count;

        // Get current time.
        $now = Carbon::now();
        // Combine exam date with exam start time to get a Carbon object for exam start time.
        $waktuMulaiUjian = Carbon::parse($this->ujian->tanggal_ujian->format('Y-m-d') . ' ' . $this->ujian->waktu_mulai);
        // Combine exam date with exam end time to get a Carbon object for exam end time.
        $waktuSelesaiUjian = Carbon::parse($this->ujian->tanggal_ujian->format('Y-m-d') . ' ' . $this->ujian->waktu_selesai);

        // Check if the student is accessing before the exam start time.
        if ($now->lessThan($waktuMulaiUjian)) {
            session()->flash('error', 'Ujian "' . $this->ujian->nama_ujian . '" belum dimulai. Ujian akan dimulai pada ' . $waktuMulaiUjian->format('d F Y H:i') . ' WIB.');
            return redirect()->route('santri.dashboard-ujian');
        }

        // Check if the student is accessing after the exam end time.
        if ($now->greaterThanOrEqualTo($waktuSelesaiUjian)) {
            // If the exam has passed its end time, submit the exam directly.
            // Use firstOrNew to get an instance of HasilUjian, either existing or new.
            $hasilUjian = HasilUjian::firstOrNew(['santri_id' => $this->santri->id, 'ujian_id' => $this->ujian->id]);
            
            // Check if the exam result already exists and its status is not 'selesai'.
            // This prevents repeated updates if the student tries to re-enter after finishing.
            if ($hasilUjian->exists && $hasilUjian->status !== 'selesai') {
                $hasilUjian->update([
                    'status' => 'selesai',
                    'waktu_selesai' => $waktuSelesaiUjian, // Set end time according to exam schedule
                    'nilai_akhir' => 0 // Or recalculate score if needed from existing answers
                ]);
                $this->santri->update([
                    'status_santri' => 'sedang_ujian', // Change status to 'sedang_ujian'
                ]);
            }
            // If it's a new exam result (doesn't exist), it means the student tried to start the exam after it ended
            // and no previous answer data was saved. We still set the status to finished.
            elseif (!$hasilUjian->exists) {
                $hasilUjian->fill([
                    'status' => 'selesai',
                    'waktu_mulai' => $now, // Current time when trying to enter
                    'waktu_selesai' => $waktuSelesaiUjian,
                    'nilai_akhir' => 0
                ])->save();
                 $this->santri->update([
                    'status_santri' => 'sedang_ujian', // Change status to 'sedang_ujian'
                ]);
            }
            session()->flash('error', 'Waktu untuk mengerjakan ujian "' . $this->ujian->nama_ujian . '" sudah berakhir pada ' . $waktuSelesaiUjian->format('d F Y H:i') . ' WIB.');
            return redirect()->route('santri.dashboard-ujian');
        }


        // Find or create the HasilUjian record for this student and exam.
        // The exam end time is calculated based on the exam date and end time from the 'ujians' table.
        $this->hasilUjian = HasilUjian::firstOrCreate(
            ['santri_id' => $this->santri->id, 'ujian_id' => $this->ujian->id],
            [
                'status' => 'sedang_mengerjakan',
                'waktu_mulai' => $now, // Use current time as actual start time
                'waktu_selesai' => $waktuSelesaiUjian // Use end time from exam configuration
            ]
        );

        // If the exam result status is already 'selesai', it means the student has already completed this exam.
        if ($this->hasilUjian->status === 'selesai') {
            session()->flash('message', 'Anda sudah menyelesaikan ujian ini.');
            return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
        }
        
        // Calculate the remaining exam time from the end time stored in hasilUjian.
        // This is the remaining time RELATIVE to the total duration set for the exam.
        $waktuSelesaiDariHasilUjian = Carbon::parse($this->hasilUjian->waktu_selesai);
        $this->sisaWaktuDetik = $now->diffInSeconds($waktuSelesaiDariHasilUjian, false);

        // If the remaining time is already exhausted (less than or equal to 0) at mount,
        // it is likely a case where the exam has ended according to schedule.
        // We also need to ensure the exam result status is updated.
        if ($this->sisaWaktuDetik <= 0) {
            $this->submitUjian(); // Automatically submit if time runs out at mount
            session()->flash('error', 'Waktu untuk mengerjakan ujian "' . $this->ujian->nama_ujian . '" sudah berakhir.');
            return redirect()->route('santri.dashboard-ujian');
        }

        // Load existing answers from the database into the local $jawabanSiswa array.
        $jawabanUjians = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get();
        foreach ($jawabanUjians as $jawaban) {
            $this->jawabanSiswa[$jawaban->soal_id] = $jawaban->jawaban;
        }

        // Initial calculation of the number of answered questions.
        $this->hitungSoalDijawab();
    }

    /**
     * Private method to count the number of answered questions.
     * An answer is counted if it is not null and not an empty string.
     *
     * @return void
     */
    private function hitungSoalDijawab()
    {
        $this->soalDijawab = count(array_filter($this->jawabanSiswa, function ($value) {
            // An answer is counted if it's not null and not an empty string after trimming.
            return $value !== null && trim((string) $value) !== '';
        }));
    }

    /**
     * Called every second to update the remaining time.
     * If time runs out, it will call `submitUjian()`.
     *
     * @return void
     */
    public function tick()
    {
        // Get current time.
        $now = Carbon::now();
        // Combine exam date with exam end time to get a Carbon object for scheduled exam end time.
        $waktuSelesaiUjianTerjadwal = Carbon::parse($this->ujian->tanggal_ujian->format('Y-m-d') . ' ' . $this->ujian->waktu_selesai);

        // If current time exceeds the scheduled exam end time,
        // or if the remaining seconds have run out, then submit the exam.
        if ($now->greaterThanOrEqualTo($waktuSelesaiUjianTerjadwal) || $this->sisaWaktuDetik <= 0) {
            $this->sisaWaktuDetik = 0; // Ensure remaining time becomes 0.
            $this->submitUjian(); // Automatically submit the exam.
        } else {
            $this->sisaWaktuDetik--; // Decrease remaining time.
        }
    }

    /**
     * Computed property for readable countdown format (HH:MM:SS).
     *
     * @return string
     */
    #[Computed]
    public function waktuMundurFormatted()
    {
        $hours = floor($this->sisaWaktuDetik / 3600); // Calculate hours.
        $minutes = floor(($this->sisaWaktuDetik % 3600) / 60); // Calculate minutes.
        $seconds = $this->sisaWaktuDetik % 60; // Calculate seconds.
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // Return HH:MM:SS format.
    }

    /**
     * Computed property to get exam duration from exam data.
     * This duration is calculated based on waktu_mulai and waktu_selesai in the 'ujians' table.
     *
     * @return int
     */
    #[Computed]
    public function durasi()
    {
        if (!$this->ujian) return 0; // Return 0 if the exam object is not yet available.
        $waktuMulai = Carbon::parse($this->ujian->waktu_mulai); // Parse start time from 'ujians' table.
        $waktuSelesai = Carbon::parse($this->ujian->waktu_selesai); // Parse end time from 'ujians' table.
        return $waktuMulai->diffInMinutes($waktuSelesai); // Calculate difference in minutes.
    }

    /**
     * Deletes the student's answer for a specific question.
     *
     * @param int $soalId The ID of the question whose answer will be deleted.
     * @return void
     */
    public function hapusJawaban($soalId)
    {
        if ($this->isFinished) return; // Stop if the exam is already finished.
        
        // Delete the answer from the local array.
        unset($this->jawabanSiswa[$soalId]);

        // Delete the answer from the database.
        JawabanUjian::where(['hasil_ujian_id' => $this->hasilUjian->id, 'soal_id' => $soalId])->delete();

        // Recalculate the progress of answered questions.
        $this->hitungSoalDijawab();

        // Emit an event to the frontend that the answer has been updated.
        $this->dispatch('jawaban-updated', soalId: $soalId);
    }

    /**
     * Saves the student's answer for a specific question.
     *
     * @param int $soalId Question ID.
     * @param string|null $jawaban Answer given by the student.
     * @return void
     */
    public function simpanJawaban($soalId, $jawaban)
    {
        // If the answer is empty or null, call the hapusJawaban method.
        if ($jawaban === null || $jawaban === '') {
            return $this->hapusJawaban($soalId);
        }
        
        // Save the answer to the local array.
        $this->jawabanSiswa[$soalId] = $jawaban;
        
        // Save or update the answer in the database.
        JawabanUjian::updateOrCreate(
            ['hasil_ujian_id' => $this->hasilUjian->id, 'soal_id' => $soalId],
            ['jawaban' => $jawaban]
        );
    
        // Recalculate the progress of answered questions.
        $this->hitungSoalDijawab();
        // Emit an event to the frontend that the answer has been updated.
        $this->dispatch('jawaban-updated', soalId: $soalId);
    }
    
    /**
     * Checks unanswered questions and prepares a modal message.
     *
     * @return void
     */
    public function checkUnansweredQuestions()
    {
        $soals = $this->ujian->soals; // Get all exam questions.
        $unansweredQuestions = []; // Array to store the numbers of unanswered questions.
        foreach ($soals as $index => $soal) {
            // Check if the answer for this question is missing or empty.
            if (!isset($this->jawabanSiswa[$soal->id]) || empty($this->jawabanSiswa[$soal->id])) {
                $unansweredQuestions[] = $index + 1; // Add the question number to the unanswered list.
            }
        }
        // Prepare the modal message based on whether there are unanswered questions.
        if (count($unansweredQuestions) > 0) {
            $this->modalMessage = 'Soal nomor ' . implode(', ', $unansweredQuestions) . ' belum dijawab. Apakah Anda yakin ingin mengumpulkan ujian?';
        } else {
            $this->modalMessage = 'Semua soal sudah dijawab. Apakah Anda yakin ingin mengumpulkan ujian?';
        }
    }

    /**
     * Displays a confirmation modal before submitting the exam.
     *
     * @return void
     */
     public function confirmSubmit()
     {
         $this->checkUnansweredQuestions(); // Check for unanswered questions.
         $this->showModal = true; // Show the modal.
         $this->dispatch('show-modal'); // Emit an event to the frontend to display the modal.
         $this->gotoPage($this->currentPage + 1);
     }
 
     /**
      * Submits the exam and processes the results.
      * Performs a database transaction to ensure data integrity.
      *
      * @return \Illuminate\Http\RedirectResponse|void
      */
      public function submitUjian()
      {
          // Save all answers first
          foreach ($this->jawabanSiswa as $soalId => $jawaban) {
              $this->simpanJawaban($soalId, $jawaban);
          }

          // Calculate total score
          $totalScore = 0;
          $soals = $this->ujian->soals;
          
          Log::info('Starting score calculation', [
              'ujian_id' => $this->ujian->id,
              'santri_id' => $this->santri->id,
              'total_soal' => count($soals)
          ]);
          
          foreach ($soals as $soal) {
              $jawaban = $this->jawabanSiswa[$soal->id] ?? null;
              
              if ($soal->tipe_soal === 'pg' && $jawaban !== null) {
                  // Convert numeric answer (0, 1, 2, 3) to index
                  $answerIndex = (int)$jawaban;
                  
                  Log::info('Processing PG answer', [
                      'soal_id' => $soal->id,
                      'jawaban' => $jawaban,
                      'answerIndex' => $answerIndex,
                      'opsi' => $soal->opsi
                  ]);
                  
                  // Get the points for the selected option
                  if (isset($soal->opsi[$answerIndex]['bobot'])) {
                      $poinPG = (float)$soal->opsi[$answerIndex]['bobot'];
                      $totalScore += $poinPG;
                      
                      Log::info('PG score calculated', [
                          'soal_id' => $soal->id,
                          'poinPG' => $poinPG,
                          'totalScore' => $totalScore
                      ]);
                      
                      // Update nilai in jawaban_ujian table
                      $jawabanUjian = JawabanUjian::where([
                          'hasil_ujian_id' => $this->hasilUjian->id,
                          'soal_id' => $soal->id
                      ])->first();

                      if ($jawabanUjian) {
                          $jawabanUjian->update([
                              'nilai' => $poinPG,
                              'jawaban' => $jawaban
                          ]);
                          
                          Log::info('Jawaban updated', [
                              'soal_id' => $soal->id,
                              'nilai' => $poinPG,
                              'jawaban' => $jawaban
                          ]);
                  }
              }
              } elseif ($soal->tipe_soal === 'essay' && $jawaban !== null) {
                  if (isset($soal->bobot)) {
                      $poinEssay = (float)$soal->bobot;
                      $totalScore += $poinEssay;
                      
                      Log::info('Essay score calculated', [
                          'soal_id' => $soal->id,
                          'poinEssay' => $poinEssay,
                          'totalScore' => $totalScore
                      ]);
                      
                      // Update nilai in jawaban_ujian table
                      $jawabanUjian = JawabanUjian::where([
                          'hasil_ujian_id' => $this->hasilUjian->id,
                          'soal_id' => $soal->id
                      ])->first();

                      if ($jawabanUjian) {
                          $jawabanUjian->update([
                              'nilai' => $poinEssay,
                              'jawaban' => $jawaban
                          ]);
                          
                          Log::info('Essay answer updated', [
                              'soal_id' => $soal->id,
                              'nilai' => $poinEssay,
                              'jawaban' => $jawaban
                          ]);
                      }
                  }
              }
          }

          Log::info('Final score calculation', [
              'totalScore' => $totalScore,
              'hasilUjianId' => $this->hasilUjian->id
          ]);

          // Update hasil ujian
          $this->hasilUjian->update([
              'nilai_akhir' => $totalScore,
              'status' => 'selesai',
              'waktu_selesai' => now()
          ]);

          // Update student status
          $this->santri->update([
              'status_santri' => 'sedang_ujian'
          ]);

          // Calculate and update total score for all exams
          $semuaHasilUjian = HasilUjian::where('santri_id', $this->santri->id)
              ->where('status', 'selesai')
              ->get();

          $totalNilaiSemuaUjian = $semuaHasilUjian->sum('nilai_akhir');
          $rataRataUjian = $semuaHasilUjian->avg('nilai_akhir');

          $this->santri->update([
              'total_nilai_semua_ujian' => $totalNilaiSemuaUjian,
              'rata_rata_ujian' => $rataRataUjian
          ]);

          // Redirect to selesai ujian page
          return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
      }

    /**
     * Navigates to a specific question page.
     * Saves the current question's answer before navigating.
     *
     * @param int $pageNumber The target question page number.
     * @return void
     */
    public function gotoPage($pageNumber)
    {
        if ($pageNumber >= 1 && $pageNumber <= $this->jumlahSoal) {
            // Get the current question to save its answer if any.
            $soals = $this->ujian->soals()->orderBy('id')->get();
            $currentSoal = $soals[$this->currentPage - 1] ?? null;

            // If there's a current question and an answer bound to the input, save the answer.
            // This is crucial for persistence when navigating between pages.
            if ($currentSoal && isset($this->jawabanSiswa[$currentSoal->id])) {
                $this->simpanJawaban($currentSoal->id, $this->jawabanSiswa[$currentSoal->id]);
            }
            
            $this->currentPage = $pageNumber; // Set the current question page.
            // No need to reset ['jawaban'] here if using $jawabanSiswa[$currentSoal->id] directly in the blade.
            // The blade will pick up the existing value from $jawabanSiswa array.
        }
    }
    
    /**
     * Navigates to the next question page.
     *
     * @return void
     */
    public function nextPage()
    {
        if ($this->currentPage < $this->jumlahSoal) {
            $this->gotoPage($this->currentPage + 1); // Call gotoPage to move to the next page.
        }
    }
    
    /**
     * Navigates to the previous question page.
     *
     * @return void
     */
    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->gotoPage($this->currentPage - 1); // Call gotoPage to move to the previous page.
        }
    }

    /**
     * The render function, renders the Livewire component view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        // Get all exam questions, ordered by ID.
        $soals = $this->ujian->soals()->orderBy('id')->get();
        // Get the currently active question based on currentPage.
        $currentSoal = $soals[$this->currentPage - 1] ?? null;

        // Return the Blade view along with the necessary data.
        return view('livewire.santri-p-p-d-b.mulai-ujian', [
            'soals' => $soals, // All exam questions.
            'currentSoal' => $currentSoal, // The currently displayed question.
            'jawabanSiswa' => $this->jawabanSiswa, // Student's saved answers.
            'durasi' => $this->durasi // Exam duration.
        ]);
    }
}
