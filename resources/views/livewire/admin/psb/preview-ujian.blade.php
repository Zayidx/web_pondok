@extends('components.layouts.preview-ujian')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="bg-white rounded-xl card-shadow p-6 mb-8 hover-lift">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $ujian->nama_ujian }}</h1>
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

    <!-- Info Section -->
    <div class="grid md:grid-cols-5 gap-8 mb-8">
        <!-- Exam Info -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl card-shadow p-6 h-full hover-lift">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                    Informasi Ujian
                </h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-semibold text-gray-700">Tanggal</span>
                        <span class="text-gray-600">{{ $ujian->tanggal_ujian->format('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-semibold text-gray-700">Waktu</span>
                        <span class="text-gray-600">{{ $ujian->waktu_mulai }} - {{ $ujian->waktu_selesai }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-semibold text-gray-700">Durasi</span>
                        <span class="text-gray-600">{{ $ujian->durasi }} menit</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="font-semibold text-gray-700">Jumlah Soal</span>
                        <span class="text-gray-600">{{ $ujian->soals->count() }} soal</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="md:col-span-3">
            <div class="bg-white rounded-xl card-shadow p-6 h-full hover-lift">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <div class="w-2 h-8 bg-green-600 rounded-full"></div>
                    Petunjuk Pengerjaan
                </h2>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mt-0.5">1</div>
                        <p class="text-gray-700">Baca bismillah sebelum mengerjakan</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mt-0.5">2</div>
                        <p class="text-gray-700">Kerjakan soal sesuai dengan waktu yang ditentukan</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mt-0.5">3</div>
                        <p class="text-gray-700">Untuk soal pilihan ganda, pilih satu jawaban yang paling tepat</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mt-0.5">4</div>
                        <p class="text-gray-700">Untuk soal essay, jawab dengan jelas dan lengkap</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mt-0.5">5</div>
                        <p class="text-gray-700">Pastikan semua soal terjawab sebelum mengirim jawaban</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions Section -->
    <div class="space-y-6">
        @foreach($ujian->soals as $index => $soal)
            <div class="bg-white rounded-xl card-shadow overflow-hidden hover-lift">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <!-- Question Number -->
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg shadow-lg flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        
                        <div class="flex-1">
                            <!-- Question Type Badge -->
                            <div class="flex items-center justify-between mb-4">
                                @if($soal->tipe_soal === 'pg')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Pilihan Ganda
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Essay
                                    </span>
                                @endif
                            </div>

                            <!-- Question Text -->
                            <p class="text-lg text-gray-800 mb-6 leading-relaxed">{{ $soal->pertanyaan }}</p>

                            <!-- Answer Options -->
                            @if($soal->tipe_soal === 'pg')
                                <div class="space-y-3">
                                    @foreach($soal->opsi as $opsiIndex => $opsi)
                                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition duration-200 group">
                                            <input type="radio" name="soal_{{ $soal->id }}" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 focus:ring-2" disabled>
                                            <div class="ml-4 flex-1 flex items-center justify-between">
                                                <span class="text-gray-800 group-hover:text-blue-800">
                                                    <span class="font-semibold mr-2">{{ chr(65 + $opsiIndex) }}.</span>
                                                    {{ $opsi['teks'] }}
                                                </span>
                                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $opsi['bobot'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                    {{ $opsi['bobot'] }} poin
                                                </span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="space-y-3">
                                    <textarea class="w-full p-4 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 resize-none transition duration-200" rows="4" placeholder="Tulis jawaban essay di sini..." disabled></textarea>
                                    <p class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                        Jawab dengan jelas dan lengkap untuk mendapatkan nilai maksimal
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 