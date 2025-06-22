<div>
    <div class="gradient-bg min-h-screen">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="bg-white rounded-xl card-shadow p-6 mb-8 hover-lift">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $ujian->nama_ujian }}</h1>
                        <p class="text-lg text-gray-600">Mode Pratinjau Admin</p>
                    </div>

                    <a href="{{ route('admin.master-ujian.detail', $ujian->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H3" />
                        </svg>
                        Keluar dari Preview
                    </a>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white rounded-lg card-shadow overflow-hidden">
                        <div class="bg-primary p-4">
                            <h2 class="text-lg font-semibold text-white flex items-center gap-2">Informasi Ujian</h2>
                        </div>
                        <div class="p-5">
                            <table class="w-full">
                                <tbody>
                                    <tr>
                                        <td class="py-3 font-medium text-gray-700">Mata Pelajaran</td>
                                        <td class="py-3 text-gray-600">: {{ $ujian->mata_pelajaran }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-medium text-gray-700">Tanggal</td>
                                        <td class="py-3 text-gray-600">: {{ \Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-medium text-gray-700">Waktu</td>
                                        <td class="py-3 text-gray-600">: {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-medium text-gray-700">Jumlah Soal</td>
                                        <td class="py-3 text-gray-600">: {{ $jumlahSoal }} soal</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg card-shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Navigasi Soal</h3>
                        <div class="grid grid-cols-5 gap-2" wire:key="navigasi-soal">
                            @if($soals)
                            @foreach($soals as $index => $soal)
                            <button wire:click="gotoPage({{ $index + 1 }})"
                                class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-medium
                                        {{ ($index + 1) == $currentPage ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}"
                                wire:key="nav-soal-{{ $index + 1 }}">
                                {{ $index + 1 }}
                            </button>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    @if($currentSoal)
                    <div class="bg-white rounded-xl card-shadow overflow-hidden mb-6">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg shadow-lg flex-shrink-0">{{ $currentPage }}</div>
                                <div class="flex-1">
                                    <p class="text-lg text-gray-800 mb-6 leading-relaxed">{!! $currentSoal->pertanyaan !!}</p>

                                    @if ($currentSoal->tipe_soal === 'pg')
                                    <div class="space-y-3" wire:key="soal-{{ $currentSoal->id }}-pg">
                                        @if(!empty($currentSoal->opsi))
                                            @foreach ($currentSoal->opsi as $key => $opsi)
                                            <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg"
                                                wire:key="opsi-{{ $currentSoal->id }}-{{ $key }}">

                                                <input type="radio" name="question_{{ $currentSoal->id }}" class="w-5 h-5 text-blue-600" disabled>

                                                <div class="ml-4 flex-1">
                                                    <span class="text-gray-800">
                                                        <span class="font-semibold mr-2">{{ chr(65 + $key) }}.</span>
                                                        {{ $opsi['teks'] ?? '' }}
                                                    </span>
                                                </div>
                                                
                                                <span class="ml-auto text-sm font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                                    Poin: {{ $opsi['bobot'] ?? 0 }}
                                                </span>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    @else
                                        <div class="p-4 bg-gray-100 rounded-lg">
                                            <p class="text-center text-gray-600">Ini adalah soal tipe Essay. Jawaban akan diperiksa secara manual oleh admin.</p>
                                        </div>
                                    @endif

                                    <div class="flex justify-between items-center mt-6 w-full">
                                        <button wire:click="previousPage" @if($currentPage==1) disabled @endif class="px-4 py-2 flex items-center gap-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                            Sebelumnya
                                        </button>
                                        <button wire:click="nextPage" @if($currentPage >= $jumlahSoal) disabled @endif class="px-4 py-2 flex items-center gap-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                            Selanjutnya
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-white rounded-xl card-shadow p-6">
                        <p class="text-gray-600 text-center">Tidak ada soal yang tersedia untuk ujian ini atau pilih nomor soal untuk memulai pratinjau.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>