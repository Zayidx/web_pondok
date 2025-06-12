<div class="gradient-bg min-h-screen flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Confirmation Card -->
        <div class="bg-white rounded-xl card-shadow p-8 text-center">
            <!-- Icon -->
            <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Konfirmasi Mulai Ujian</h1>
            <p class="text-lg text-gray-600 mb-8">Pastikan Anda siap sebelum memulai ujian</p>

            <!-- Exam Details -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <div class="w-2 h-6 bg-primary rounded-full"></div>
                    Detail Ujian
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Mata Pelajaran</span>
                        <span class="text-gray-900">{{ $ujian->mata_pelajaran }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Jenis Ujian</span>
                        <span class="text-gray-900">{{ $ujian->jenis_ujian }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Tanggal</span>
                        <span class="text-gray-900">{{ $ujian->tanggal_ujian->format('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Waktu</span>
                        <span class="text-gray-900">{{ $ujian->waktu_mulai }} - {{ $ujian->waktu_selesai }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Durasi</span>
                        <span class="text-gray-900">{{ $durasi }} menit</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Jumlah Soal</span>
                        <span class="text-gray-900">{{ $jumlah_soal }} soal</span>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8 text-left">
                <h3 class="text-lg font-bold text-yellow-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Perhatian Penting!
                </h3>
                <ul class="space-y-2 text-yellow-700">
                    <li class="flex items-start gap-2">
                        <span class="text-yellow-600 mt-1">•</span>
                        <span>Pastikan koneksi internet stabil selama ujian</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-yellow-600 mt-1">•</span>
                        <span>Ujian akan otomatis berakhir setelah waktu habis</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-yellow-600 mt-1">•</span>
                        <span>Jawaban akan tersimpan otomatis setiap 30 detik</span>
                    </li>
                </ul>
            </div>

            <!-- Checklist -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8 text-left">
                <h3 class="text-lg font-bold text-green-800 mb-3">Checklist Persiapan</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="checklist.alat_tulis" class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                        <span class="text-green-700">Saya telah menyiapkan alat tulis (jika diperlukan)</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="checklist.koneksi" class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                        <span class="text-green-700">Koneksi internet saya stabil</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="checklist.petunjuk" class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                        <span class="text-green-700">Saya telah membaca semua petunjuk ujian</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="checklist.siap" class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                        <span class="text-green-700">Saya siap untuk memulai ujian</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('santri.dashboard-ujian') }}" class="px-8 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition duration-300">
                    Kembali ke Dashboard
                </a>
                <button wire:click="mulaiUjian" 
                    @class([
                        'px-8 py-3 rounded-lg font-medium transition duration-300 shadow-lg',
                        'bg-primary text-white hover:bg-primary-dark' => $checklist['alat_tulis'] && $checklist['koneksi'] && $checklist['petunjuk'] && $checklist['siap'],
                        'bg-gray-300 text-gray-500 cursor-not-allowed' => !$checklist['alat_tulis'] || !$checklist['koneksi'] || !$checklist['petunjuk'] || !$checklist['siap']
                    ])
                    @if(!$checklist['alat_tulis'] || !$checklist['koneksi'] || !$checklist['petunjuk'] || !$checklist['siap']) disabled @endif>
                    Mulai Ujian Sekarang
                </button>
            </div>
        </div>
    </div>
</div> 