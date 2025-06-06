@push('styles')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
    }
    .card-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    .score-animation {
        animation: scoreReveal 1.5s ease-out forwards;
    }
    @keyframes scoreReveal {
        0% { transform: scale(0.5); opacity: 0; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
    .progress-bar {
        animation: progressFill 2s ease-out forwards;
    }
    @keyframes progressFill {
        0% { width: 0%; }
        100% { width: var(--progress-width); }
    }
</style>
@endpush

<div class="gradient-bg min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-8 hover-lift">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Hasil Ujian</h1>
                    <p class="text-lg text-gray-600">{{ $santri->nama_lengkap }}</p>
                </div>
                <a href="{{ route('santri.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium transition duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Score Overview -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <!-- Main Score -->
            <div class="md:col-span-2 bg-white rounded-xl card-shadow p-8 text-center hover-lift">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $ujian->nama_ujian }}</h2>
                <div class="score-animation">
                    @if($hasilUjian->nilai !== null)
                        <div class="text-6xl font-bold text-green-600 mb-2">{{ $hasilUjian->nilai }}</div>
                        <div class="text-xl text-gray-600 mb-4">dari 100</div>
                        <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $hasilUjian->nilai >= 75 ? 'Lulus' : 'Tidak Lulus' }} - Grade {{ $this->getNilaiGrade($hasilUjian->nilai) }}
                        </div>
                    @else
                        <div class="text-4xl font-bold text-gray-600 mb-2">Nilai Belum Tersedia</div>
                        <div class="text-xl text-gray-500 mb-4">Hasil ujian sedang dalam proses penilaian</div>
                        <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-medium">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Menunggu Penilaian
                        </div>
                    @endif
                </div>
                <div class="mt-6 text-sm text-gray-500">
                    Dikerjakan pada: {{ $waktuMulai->format('d F Y, H:i') }} - {{ $waktuSelesai->format('H:i') }}
                </div>
            </div>

            <!-- Grade Info -->
            <div class="bg-white rounded-xl card-shadow p-6 hover-lift">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <div class="w-2 h-6 bg-primary rounded-full"></div>
                    Informasi Nilai
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Nilai Anda</span>
                        <span class="font-bold {{ $hasilUjian->nilai !== null ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $hasilUjian->nilai !== null ? $hasilUjian->nilai : 'Belum ada' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Soal Terjawab</span>
                        <span class="font-medium text-gray-900">{{ $soalTerjawab }} dari {{ $jumlahSoal }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Durasi Pengerjaan</span>
                        <span class="font-medium text-gray-900">{{ $durasiPengerjaan }} menit</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status</span>
                        <span class="font-medium {{ $hasilUjian->status === 'selesai' ? 'text-green-600' : 'text-blue-600' }}">
                            {{ $hasilUjian->status === 'selesai' ? 'Selesai' : 'Menunggu Penilaian' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if($hasilUjian->nilai !== null)
            <!-- Detailed Breakdown -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <!-- Score Breakdown -->
                <div class="bg-white rounded-xl card-shadow p-6 hover-lift">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                        Rincian Nilai
                    </h3>
                    <div class="space-y-4">
                        <!-- Pilihan Ganda -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-gray-700">Pilihan Ganda ({{ $jumlahSoalPG }} soal)</span>
                                <span class="font-bold text-gray-900">{{ $nilaiPG }}/{{ $maxNilaiPG }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="progress-bar bg-green-500 h-2 rounded-full" style="--progress-width: {{ ($nilaiPG / $maxNilaiPG) * 100 }}%"></div>
                            </div>
                            <div class="text-sm text-gray-600 mt-1">{{ $jumlahBenarPG }} benar dari {{ $jumlahSoalPG }} soal</div>
                        </div>
                        
                        <!-- Essay -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-gray-700">Essay ({{ $jumlahSoalEssay }} soal)</span>
                                <span class="font-bold text-gray-900">{{ $nilaiEssay }}/{{ $maxNilaiEssay }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="progress-bar bg-blue-500 h-2 rounded-full" style="--progress-width: {{ ($nilaiEssay / $maxNilaiEssay) * 100 }}%"></div>
                            </div>
                            <div class="text-sm text-gray-600 mt-1">Rata-rata {{ number_format($nilaiEssay / $jumlahSoalEssay, 1) }} dari {{ $maxNilaiEssay / $jumlahSoalEssay }} poin per soal</div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span class="text-gray-900">Total Nilai</span>
                                <span class="text-green-600">{{ $hasilUjian->nilai }}/100</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Analysis -->
                <div class="bg-white rounded-xl card-shadow p-6 hover-lift">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <div class="w-2 h-8 bg-purple-600 rounded-full"></div>
                        Analisis Performa
                    </h3>
                    <div class="space-y-4">
                        @if($nilaiPG > ($maxNilaiPG * 0.7))
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="font-medium text-green-800">Kekuatan</span>
                                </div>
                                <span class="text-green-700">Pilihan Ganda</span>
                            </div>
                        @endif
                        
                        @if($nilaiEssay < ($maxNilaiEssay * 0.7))
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <span class="font-medium text-yellow-800">Perlu Diperbaiki</span>
                                </div>
                                <span class="text-yellow-700">Soal Essay</span>
                            </div>
                        @endif

                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2">Rekomendasi</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                @if($nilaiEssay < ($maxNilaiEssay * 0.7))
                                    <li>• Perbanyak latihan soal essay</li>
                                    <li>• Pelajari cara menjawab dengan sistematis</li>
                                @endif
                                @if($nilaiPG < ($maxNilaiPG * 0.7))
                                    <li>• Perbanyak latihan soal pilihan ganda</li>
                                    <li>• Teliti dalam membaca soal dan pilihan jawaban</li>
                                @endif
                                <li>• Konsultasi dengan guru untuk materi yang sulit</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Waiting for Results -->
            <div class="bg-white rounded-xl card-shadow p-8 mb-8 text-center">
                <div class="mb-6">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary mx-auto"></div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Hasil Ujian Sedang Dinilai</h3>
                <p class="text-gray-600 max-w-lg mx-auto">
                    Mohon tunggu beberapa saat. Hasil ujian Anda sedang dalam proses penilaian oleh tim pengajar. 
                    Anda akan mendapatkan notifikasi setelah hasil penilaian selesai.
                </p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('santri.dashboard') }}" class="px-8 py-3 bg-primary text-white rounded-lg font-medium hover:bg-primary-dark transition duration-300 shadow-lg">
                Kembali ke Dashboard
            </a>
            @if($hasilUjian->nilai !== null)
                <button class="px-8 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition duration-300">
                    Download Hasil PDF
                </button>
                <button class="px-8 py-3 border-2 border-primary text-primary rounded-lg font-medium hover:bg-blue-50 transition duration-300">
                    Lihat Pembahasan Lengkap
                </button>
            @endif
        </div>
    </div>
</div> 