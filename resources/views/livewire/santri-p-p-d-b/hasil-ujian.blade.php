<div>
    @php
        $letterToIndex = function($letter) {
            return ord(strtoupper($letter)) - ord('A');
        };
    @endphp

    <div class="gradient-bg min-h-screen">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="bg-white rounded-xl card-shadow p-6 mb-8 hover-lift">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $ujian->nama_ujian }}</h1>
                        <p class="text-lg text-gray-600">{{ $santri->nama_lengkap }}</p>
                    </div>
                    <div class="bg-red-100 px-4 py-2 rounded-lg">
                        <div class="text-center">
                            <div id="exam-timer">
                                <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                            </div>
                            <p class="text-red-600 text-sm">Sisa Waktu</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-white rounded-xl card-shadow p-6 mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress Ujian</span>
                    <span class="text-sm text-gray-500">{{ $soalDijawab }} dari {{ $jumlahSoal }} soal</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full transition-all duration-300" 
                         style="width: {{ ($soalDijawab / $jumlahSoal) * 100 }}%">
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Sidebar - Exam Info -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Exam Information -->
                    <div class="bg-white rounded-lg card-shadow overflow-hidden">
                        <div class="bg-primary p-4">
                            <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Informasi Ujian
                            </h2>
                        </div>
                        <div class="p-5">
                            <table class="w-full">
                                <tbody>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 font-medium text-gray-700">Mata Pelajaran</td>
                                        <td class="py-3 text-gray-600">: {{ $ujian->mata_pelajaran }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 font-medium text-gray-700">Tanggal</td>
                                        <td class="py-3 text-gray-600">: {{ $ujian->tanggal_ujian->format('d M Y') }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 font-medium text-gray-700">Waktu</td>
                                        <td class="py-3 text-gray-600">: {{ $ujian->waktu_mulai }} - {{ $ujian->waktu_selesai }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 font-medium text-gray-700">Durasi</td>
                                        <td class="py-3 text-gray-600">: {{ $durasi }} menit</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-medium text-gray-700">Jumlah Soal</td>
                                        <td class="py-3 text-gray-600">: {{ $jumlahSoal }} soal</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Question Navigation -->
                    <div class="bg-white rounded-lg card-shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Navigasi Soal</h3>
                        <div class="grid grid-cols-5 gap-2">
                            @for ($i = 1; $i <= $jumlahSoal; $i++)
                                <button wire:click="gotoPage({{ $i }})" 
                                    class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-medium
                                        {{ $i == $currentPage ? 'bg-primary text-white' : 
                                        (isset($jawabanSiswa[$soals[$i-1]->id]) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600') }}
                                        hover:bg-primary hover:text-white transition duration-200">
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Main Content - Question -->
                <div class="md:col-span-2">
                    @if($currentSoal)
                    <!-- Current Question -->
                    <div class="bg-white rounded-xl card-shadow overflow-hidden mb-6">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg shadow-lg flex-shrink-0">{{ $currentPage }}</div>
                                
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $currentSoal->tipe_soal === 'pg' ? 'Pilihan Ganda' : 'Essay' }}
                                        </span>
                                    </div>

                                    <p class="text-lg text-gray-800 mb-6 leading-relaxed">{{ $currentSoal->pertanyaan }}</p>

                                    @if($currentSoal->tipe_soal === 'pg')
                                        <div class="space-y-3">
                                            @foreach ($currentSoal->opsi as $key => $opsi)
                                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition duration-200 group">
                                                    <input type="radio"
                                                        wire:model="jawabanSiswa.{{ $currentSoal->id }}"
                                                        wire:change="simpanJawaban({{ $currentSoal->id }}, {{ $letterToIndex($key) }})"
                                                        name="question_{{ $currentSoal->id }}"
                                                        value="{{ $letterToIndex($key) }}"
                                                        class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                    <div class="ml-4 flex-1">
                                                        <span class="text-gray-800 group-hover:text-blue-800">
                                                            <span class="font-semibold mr-2">{{ $key }}.</span>
                                                            {{ $opsi['teks'] }}
                                                        </span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="space-y-3">
                                            <textarea 
                                                wire:model="jawabanSiswa.{{ $currentSoal->id }}"
                                                wire:change="simpanJawaban({{ $currentSoal->id }}, $event.target.value)"
                                                class="w-full p-4 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 resize-none transition duration-200" 
                                                rows="4" 
                                                placeholder="Tulis jawaban essay di sini..."></textarea>
                                        </div>
                                    @endif

                                    <!-- Navigation and Submit Button -->
                                    <div class="flex justify-between items-center mt-6 w-full">
                                        <button wire:click="previousPage" 
                                            @if($currentPage == 1) disabled @endif
                                            class="px-4 py-2 flex items-center gap-2 {{ $currentPage == 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-lg transition duration-200">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Sebelumnya
                                        </button>
                                        
                                        @if($currentPage < $jumlahSoal)
                                            <button wire:click="nextPage" 
                                                class="px-4 py-2 flex items-center gap-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition duration-200">
                                                Selanjutnya
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" 
                        wire:click="confirmSubmit"
                        class="inline-flex items-center px-6 py-3 text-lg font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <span>Selesai & Kumpulkan</span>
                        <svg class="ml-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="ml-2 bg-green-700 rounded-full px-2 py-1 text-xs">
                            {{ $soalDijawab }}/{{ $jumlahSoal }}
                        </span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmation-modal" class="modal-container" wire:ignore>
        <div class="modal-backdrop" onclick="hideModal()"></div>
        <div class="modal-content">
            <div class="text-center">
                <!-- Warning Icon -->
                <div class="modal-icon">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <h3 class="modal-title">Konfirmasi Pengumpulan</h3>
                <p class="modal-message">{{ $modalMessage }}</p>

                <div class="modal-buttons">
                    <button type="button" onclick="hideModal()" class="modal-button modal-button-cancel">
                        Kembali
                    </button>
                    <button type="button" wire:click="submitUjian" onclick="hideModal()" class="modal-button modal-button-confirm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Ya, Kumpulkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Timer Logic
        function startTimer(sisaWaktu) {
            const hoursEl = document.getElementById('hours');
            const minutesEl = document.getElementById('minutes');
            const secondsEl = document.getElementById('seconds');

            function updateTimer() {
                if (sisaWaktu <= 0) {
                    clearInterval(timerInterval);
                    hoursEl.textContent = '00';
                    minutesEl.textContent = '00';
                    secondsEl.textContent = '00';
                    // Optionally trigger exam submission
                    return;
                }

                const hours = Math.floor(sisaWaktu / 3600);
                const minutes = Math.floor((sisaWaktu % 3600) / 60);
                const seconds = sisaWaktu % 60;

                hoursEl.textContent = hours.toString().padStart(2, '0');
                minutesEl.textContent = minutes.toString().padStart(2, '0');
                secondsEl.textContent = seconds.toString().padStart(2, '0');

                sisaWaktu--;
            }

            updateTimer(); // Initial update
            const timerInterval = setInterval(updateTimer, 1000);
        }

        // Modal Logic
        function showModal() {
            console.log('Show modal triggered');
            const modal = document.getElementById('confirmation-modal');
            modal.style.display = 'flex';
            modal.classList.add('modal-visible');
            console.log('Modal display:', modal.style.display);
            console.log('Modal classes:', modal.className);
            console.log('Modal z-index:', window.getComputedStyle(modal).zIndex);
        }

        function hideModal() {
            console.log('Hide modal triggered');
            const modal = document.getElementById('confirmation-modal');
            modal.style.display = 'none';
            modal.classList.remove('modal-visible');
            console.log('Modal display:', modal.style.display);
            console.log('Modal classes:', modal.className);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded');
            hideModal();
            startTimer({{ $sisa_waktu }});
        });

        // Livewire Events
        window.addEventListener('show-modal', () => {
            console.log('Livewire show-modal event received');
            showModal();
        });

        window.addEventListener('hide-modal', () => {
            console.log('Livewire hide-modal event received');
            hideModal();
        });
    </script>
    @endpush
</div>
