<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ $ujian->mata_pelajaran }}</h1>
                    <p class="text-sm text-gray-500">Soal {{ $currentSoal + 1 }} dari {{ $ujian->jumlah_soal }}</p>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary" 
                         x-data="timer({{ $sisa_waktu }})" 
                         x-init="startTimer()"
                         x-cloak>
                        <span x-text="displayTime">00:00:00</span>
                    </div>
                    <p class="text-sm text-gray-500">Sisa Waktu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Question Content -->
            <div class="prose max-w-none mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Soal {{ $currentSoal + 1 }}</h2>
                <div class="text-gray-700">
                    <!-- Question text will be here -->
                </div>
            </div>

            <!-- Answer Options -->
            <div class="space-y-4">
                <!-- Answer options will be here -->
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8">
                <button wire:click="prevSoal" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                        @if($currentSoal === 0) disabled @endif>
                    Sebelumnya
                </button>

                <div class="flex space-x-4">
                    <button wire:click="raguRagu" 
                            class="px-4 py-2 border border-yellow-300 rounded-md text-sm font-medium text-yellow-700 hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Ragu-ragu
                    </button>
                    
                    @if($currentSoal === $ujian->jumlah_soal - 1)
                        <button wire:click="selesaiUjian" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Selesai
                        </button>
                    @else
                        <button wire:click="nextSoal" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Selanjutnya
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function timer(initialSeconds) {
        return {
            seconds: initialSeconds,
            displayTime: '00:00:00',
            interval: null,
            startTimer() {
                this.interval = setInterval(() => {
                    this.seconds--;
                    if (this.seconds <= 0) {
                        clearInterval(this.interval);
                        Livewire.dispatch('timeUp');
                    }
                    this.displayTime = this.formatTime(this.seconds);
                }, 1000);
            },
            formatTime(seconds) {
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = seconds % 60;
                return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            }
        }
    }
</script>
@endpush 