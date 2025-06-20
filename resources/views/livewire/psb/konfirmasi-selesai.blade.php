<div class="gradient-bg min-h-screen flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Confirmation Card -->
        <div class="bg-white rounded-xl card-shadow p-8 text-center">
            <!-- Icon -->
            <div class="bg-yellow-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Konfirmasi Selesai Ujian</h1>
            <p class="text-lg text-gray-600 mb-8">Pastikan Anda sudah yakin dengan semua jawaban sebelum mengumpulkan</p>

            <!-- Progress Info -->
            <div class="bg-white rounded-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress Ujian</span>
                    <span class="text-sm text-gray-500">{{ $soalDijawab }} dari {{ $jumlahSoal }} soal</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                    <div class="bg-primary h-2 rounded-full transition-all duration-300" 
                        style="width: {{ ($soalDijawab / $jumlahSoal) * 100 }}%">
                    </div>
                </div>

                @if(count($belumDijawab) > 0)
                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg text-left">
                        <h3 class="text-sm font-semibold text-yellow-800 mb-2">Soal yang belum dijawab:</h3>
                        <p class="text-yellow-700">
                            Nomor {{ implode(', ', $belumDijawab) }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Exam Details -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                <h3 class="font-semibold text-gray-900 mb-4">Detail Ujian</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Mata Pelajaran</p>
                        <p class="font-medium text-gray-900">{{ $ujian->mata_pelajaran }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jumlah Soal</p>
                        <p class="font-medium text-gray-900">{{ $jumlahSoal }} Soal</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button wire:click="kembaliKeUjian" 
                    class="px-8 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition duration-300">
                    Kembali ke Ujian
                </button>
                <button wire:click="selesaiUjian"
                    class="px-8 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-300 shadow-lg">
                    Selesai & Kumpulkan
                </button>
            </div>
        </div>
    </div>
</div> 