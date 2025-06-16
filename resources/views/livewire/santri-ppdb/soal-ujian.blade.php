@php
    $totalSoal = count($soals);
@endphp

<div class="container py-6">
    @if (session()->has('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('warning') }}</span>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg">
        <!-- Header -->
        <div class="border-b border-gray-200 p-4">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">{{ $ujian->nama_ujian }}</h1>
                <div class="text-sm text-gray-600">
                    Sisa Waktu: <span x-data x-init="$wire.on('updateTimer', time => $el.textContent = time)">{{ gmdate('H:i:s', $sisa_waktu) }}</span>
                </div>
            </div>
        </div>

        <!-- Navigation Numbers -->
        <div class="p-4 border-b border-gray-200">
            <div class="grid grid-cols-10 gap-2">
                @foreach($soals as $index => $soal)
                    <button wire:click="$set('currentSoal', {{ $index }})"
                        class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-medium
                            {{ $index === $currentSoal ? 'bg-blue-600 text-white' : 
                            (isset($jawaban[$soal->id]) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600') }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Question Content -->
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900">Soal {{ $currentSoal + 1 }}</h2>
                <p class="mt-2 text-gray-600">{{ $soals[$currentSoal]->pertanyaan }}</p>
            </div>

            <!-- Answer Section -->
            <div class="space-y-4">
                @if($soals[$currentSoal]->tipe_soal === 'pg')
                    @foreach($soals[$currentSoal]->opsi as $index => $opsi)
                        <button wire:click="toggleJawaban('{{ $index }}')"
                            class="w-full text-left p-4 rounded-lg border {{ isset($jawaban[$soals[$currentSoal]->id]) && $jawaban[$soals[$currentSoal]->id] == $index ? 'bg-blue-50 border-blue-500' : 'border-gray-300 hover:border-blue-500' }} transition-colors duration-200">
                            {{ chr(65 + $index) }}. {{ $opsi['teks'] }}
                        </button>
                    @endforeach
                @else
                    <div>
                        <textarea wire:model.live="jawaban.{{ $soals[$currentSoal]->id }}"
                            class="w-full h-32 p-4 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Tulis jawaban Anda di sini..."></textarea>
                    </div>
                @endif
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-6 flex justify-between items-center">
                <button wire:click="prevSoal"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 {{ $currentSoal === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $currentSoal === 0 ? 'disabled' : '' }}>
                    Soal Sebelumnya
                </button>
                
                <div class="flex space-x-4">
                    @if($currentSoal === count($soals) - 1)
                        <button wire:click="$dispatch('confirmSelesai')"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Selesai & Kumpulkan
                        </button>
                    @else
                        <button wire:click="nextSoal"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Soal Berikutnya
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-data="{ show: false }"
         x-show="show"
         @confirmSelesai.window="show = true"
         @close-modal.window="show = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto">
        
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="show = false"></div>

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg max-w-lg w-full p-6" @click.stop>
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Konfirmasi Pengumpulan</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        @if($belumDijawab > 0)
                            Anda masih memiliki {{ $belumDijawab }} soal yang belum dijawab. Apakah Anda yakin ingin mengumpulkan?
                        @else
                            Apakah Anda yakin ingin mengumpulkan semua jawaban?
                        @endif
                    </p>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button x-on:click="show = false"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Kembali
                    </button>
                    <button wire:click="submitUjian"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Ya, Kumpulkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timer Script -->
<script>
document.addEventListener('livewire:initialized', () => {
    let timerInterval;
    
    Livewire.on('startTimer', (time) => {
        if (timerInterval) clearInterval(timerInterval);
        
        let remainingTime = time;
        timerInterval = setInterval(() => {
            remainingTime--;
            if (remainingTime <= 0) {
                clearInterval(timerInterval);
                Livewire.dispatch('timeUp');
            } else {
                const hours = Math.floor(remainingTime / 3600);
                const minutes = Math.floor((remainingTime % 3600) / 60);
                const seconds = remainingTime % 60;
                const timeString = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                Livewire.dispatch('updateTimer', timeString);
            }
        }, 1000);
    });

    Livewire.on('confirmSelesai', () => {
        console.log('confirmSelesai event received');
    });
});
</script>
                <!-- Answer Form -->
                <form wire:submit.prevent="saveJawaban({{ $soals[$currentSoal]->id }}, $event.target.jawaban.value)">
                    @if($soals[$currentSoal]->tipe_soal === 'pg')
                        <div class="space-y-3">
                            @foreach(json_decode($soals[$currentSoal]->pilihan_jawaban) as $pilihan)
                                <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" 
                                        name="jawaban" 
                                        value="{{ $pilihan }}"
                                        {{ isset($jawaban[$soals[$currentSoal]->id]) && $jawaban[$soals[$currentSoal]->id] === $pilihan ? 'checked' : '' }}
                                        class="form-radio h-4 w-4 text-blue-600">
                                    <span class="ml-3">{{ $pilihan }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <textarea name="jawaban" 
                            rows="4" 
                            class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Tulis jawaban Anda di sini...">{{ $jawaban[$soals[$currentSoal]->id] ?? '' }}</textarea>
                    @endif

                    <div class="flex justify-between mt-6">
                        <button type="button"
                            wire:click="$set('currentSoal', {{ max(0, $currentSoal - 1) }})"
                            @if($currentSoal === 0) disabled @endif
                            class="px-4 py-2 border rounded-md text-sm font-medium {{ $currentSoal === 0 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-50' }}">
                            Sebelumnya
                        </button>

                        <div class="flex gap-2">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                                Simpan Jawaban
                            </button>

                            @if($currentSoal === $totalSoal - 1)
                                <button type="button"
                                    wire:click="checkUnfinishedQuestions"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                                    Selesai & Kumpulkan
                                </button>
                            @else
                                <button type="button"
                                    wire:click="$set('currentSoal', {{ min($totalSoal - 1, $currentSoal + 1) }})"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                                    Selanjutnya
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600">Tidak ada soal tersedia.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Popup -->
    <div x-data="{ showModal: false }" 
         x-show="showModal" 
         @open-modal.window="showModal = true"
         @close-modal.window="showModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Pengumpulan</h2>
            <p class="text-gray-600 mb-4">
                Anda memiliki <span class="font-bold">{{ $belumDijawab }}</span> soal yang belum dijawab. Apakah Anda yakin ingin mengumpulkan ujian sekarang?
            </p>
            <div class="flex justify-end gap-3">
                <button 
                    @click="showModal = false" 
                    wire:click="closeModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Kembali
                </button>
                <button 
                    wire:click="submitUjian"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Ya, Kumpulkan
                </button>
            </div>
        </div>
    </div>
</div>