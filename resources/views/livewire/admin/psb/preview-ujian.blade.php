<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-xl card-shadow p-6 mb-8 hover-lift">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $ujian->nama_ujian }}</h1>
                <p class="text-lg text-gray-600">{{ $ujian->mata_pelajaran }}</p>
            </div>
            <a href="{{ route('admin.master-ujian.detail', $ujian->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium transition duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Detail Ujian
            </a>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-xl card-shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-700">Progress Preview</span>
            <span class="text-sm text-gray-500">{{ $this->currentQuestionIndex + 1 }} dari {{ $this->totalQuestions() }} soal</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $this->progress() }}%"></div>
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
                            <tr>
                                <td class="py-3 font-medium text-gray-700">Jumlah Soal</td>
                                <td class="py-3 text-gray-600">: {{ $this->totalQuestions() }} soal</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Question Navigation -->
            <div class="bg-white rounded-lg card-shadow overflow-hidden">
                <div class="bg-green-600 p-4">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Navigasi Soal
                    </h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-5 gap-2" wire:loading.class="opacity-50">
                        @foreach($questions as $index => $soal)
                            <button wire:click="goToQuestion({{ $index }})" 
                                    wire:key="question-{{ $index }}"
                                    class="w-10 h-10 rounded-lg font-medium transition-all duration-300
                                    {{ $index === $this->currentQuestionIndex ? 'bg-blue-200 text-blue-800 border-2 border-primary' : 
                                       ($soal->tipe_soal === 'pg' ? 'bg-primary text-white' : 'bg-amber-500 text-white') }}">
                                    {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions -->
        <div class="md:col-span-2">
            <div wire:loading.delay class="bg-white rounded-xl card-shadow p-6 mb-6">
                <div class="flex justify-center items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                    <span class="ml-2">Loading...</span>
                </div>
            </div>

            <div wire:loading.remove>
                @if($currentQuestion)
                    <!-- Current Question -->
                    <div class="bg-white rounded-xl card-shadow overflow-hidden mb-6">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg shadow-lg flex-shrink-0">
                                    {{ $this->currentQuestionIndex + 1 }}
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            {{ $currentQuestion->tipe_soal === 'pg' ? 'bg-primary text-white' : 'bg-amber-500 text-white' }}">
                                            {{ $currentQuestion->tipe_soal === 'pg' ? 'Pilihan Ganda' : 'Essay' }}
                                        </span>
                                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-medium">
                                            {{ $currentQuestion->poin }} poin
                                        </span>
                                    </div>

                                    <div class="prose max-w-none">
                                        {!! $currentQuestion->soal !!}
                                    </div>

                                    @if($currentQuestion->gambar)
                                        <div class="mt-4">
                                            <img src="{{ asset('storage/' . $currentQuestion->gambar) }}" 
                                                 alt="Gambar soal" 
                                                 class="rounded-lg max-w-full h-auto"
                                                 loading="lazy">
                                        </div>
                                    @endif

                                    @if($currentQuestion->tipe_soal === 'pg')
                                        <div class="mt-6 space-y-3">
                                            @foreach($currentQuestion->pilihan as $key => $pilihan)
                                                <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                    <input type="radio" 
                                                           name="jawaban" 
                                                           value="{{ $key }}"
                                                           class="mt-1"
                                                           wire:model="jawaban.{{ $currentQuestion->id }}">
                                                    <span class="ml-3">{{ $pilihan }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="mt-6">
                                            <textarea wire:model="jawaban.{{ $currentQuestion->id }}"
                                                      class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                                      rows="4"
                                                      placeholder="Tulis jawaban Anda di sini..."></textarea>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between">
                        <button wire:click="previousQuestion"
                                wire:loading.attr="disabled"
                                class="btn btn-secondary {{ $this->currentQuestionIndex === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $this->currentQuestionIndex === 0 ? 'disabled' : '' }}>
                            <i class="fas fa-arrow-left mr-2"></i>
                            Soal Sebelumnya
                        </button>

                        <button wire:click="nextQuestion"
                                wire:loading.attr="disabled"
                                class="btn btn-primary {{ $this->currentQuestionIndex === $this->totalQuestions() - 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $this->currentQuestionIndex === $this->totalQuestions() - 1 ? 'disabled' : '' }}>
                            Soal Selanjutnya
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                @else
                    <div class="bg-white rounded-xl card-shadow p-6 text-center">
                        <p class="text-gray-600">Tidak ada soal untuk ditampilkan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 