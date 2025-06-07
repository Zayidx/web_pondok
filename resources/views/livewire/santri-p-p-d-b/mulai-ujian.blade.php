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
                            <div x-data="timerData({{ $sisaWaktu }})" x-init="startTimer()" class="text-2xl font-bold text-red-600">
                                <span x-text="hours.toString().padStart(2, '0')">00</span>:
                                <span x-text="minutes.toString().padStart(2, '0')">00</span>:
                                <span x-text="seconds.toString().padStart(2, '0')">00</span>
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
                                        
                                        @if($currentPage == $jumlahSoal)
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
                                        @else
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
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-data="{ 
        showModal: @entangle('showModal').defer
    }" 
    x-show="showModal"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Backdrop -->
            <div x-show="showModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-50"
                @click="showModal = false">
            </div>

            <!-- Modal Content -->
            <div x-show="showModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                
                <div class="text-center">
                    <!-- Warning Icon -->
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                        <svg class="h-10 w-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Pengumpulan</h3>
                    <p class="text-gray-600 mb-6">
                        {{ $modalMessage }}
                    </p>

                    <div class="flex justify-center gap-4">
                        <button type="button"
                            @click="showModal = false"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            Kembali
                        </button>
                        <button type="button"
                            wire:click="submitUjian"
                            @click="showModal = false"
                            class="inline-flex items-center px-6 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Ya, Kumpulkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Single instance of timer function
        window.timerData = function(initialSeconds) {
            return {
                totalSeconds: initialSeconds,
                hours: Math.floor(initialSeconds / 3600),
                minutes: Math.floor((initialSeconds % 3600) / 60),
                seconds: initialSeconds % 60,
                timer: null,
                startTimer() {
                    if (this.timer) return; // Prevent multiple timers
                    
                    this.timer = setInterval(() => {
                        if (this.totalSeconds <= 0) {
                            clearInterval(this.timer);
                            this.timer = null;
                            Livewire.dispatch('waktuHabis');
                            return;
                        }
                        
                        this.totalSeconds--;
                        this.hours = Math.floor(this.totalSeconds / 3600);
                        this.minutes = Math.floor((this.totalSeconds % 3600) / 60);
                        this.seconds = this.totalSeconds % 60;
                    }, 1000);
                }
            };
        }
    </script>
    @endpush

    @once
    @push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @endpush
    @endonce
</div> 