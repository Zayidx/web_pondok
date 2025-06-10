<div class="p-6">
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Detail Ujian</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600">Nama Santri</p>
                <p class="font-medium">{{ $ujian->santri->nama }}</p>
            </div>
            <div>
                <p class="text-gray-600">Kelas</p>
                <p class="font-medium">{{ $ujian->santri->kelas->nama }}</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        @foreach($soal as $index => $s)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-medium">Soal {{ $index + 1 }}</h3>
                        <span class="text-sm text-gray-500">
                            {{ $s->jenis_soal === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Essay' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($s->jenis_soal === 'pilihan_ganda')
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium">Nilai:</span>
                                <span class="text-sm text-gray-500">{{ $jawaban->where('soal_id', $s->id)->first()?->nilai ?? 0 }}/{{ $s->nilai }}</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <input type="number" 
                                       wire:model.live="nilai.{{ $index }}" 
                                       class="w-20 px-2 py-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nilai.'.$index) border-red-500 @enderror"
                                       min="0">
                                <span class="text-sm text-gray-500">/{{ $s->nilai }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @error('nilai.'.$index)
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <div class="prose max-w-none">
                    {!! $s->soal !!}
                </div>

                <div class="mt-4">
                    <h4 class="font-medium mb-2">Jawaban Santri:</h4>
                    <div class="bg-gray-50 p-4 rounded-md">
                        @if($s->jenis_soal === 'pilihan_ganda')
                            <div class="space-y-2">
                                @php
                                    $jawabanSantri = $jawaban->where('soal_id', $s->id)->first();
                                    $jawabanBenar = $s->jawaban_benar;
                                @endphp
                                <p class="font-medium">Jawaban yang dipilih: {{ $jawabanSantri?->jawaban ?? 'Belum menjawab' }}</p>
                                <p class="font-medium">Jawaban benar: {{ $jawabanBenar }}</p>
                                <p class="font-medium {{ $jawabanSantri?->jawaban === $jawabanBenar ? 'text-green-600' : 'text-red-600' }}">
                                    Status: {{ $jawabanSantri?->jawaban === $jawabanBenar ? 'Benar' : 'Salah' }}
                                </p>
                            </div>
                        @else
                            {!! $jawaban->where('soal_id', $s->id)->first()?->jawaban ?? 'Belum menjawab' !!}
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600">Total Nilai</p>
                <p class="text-2xl font-bold">{{ number_format($totalNilai, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-600">Nilai Akhir</p>
                <p class="text-2xl font-bold">{{ number_format($nilaiAkhir, 2) }}%</p>
            </div>
        </div>

        <div class="mt-6">
            <button wire:click="simpanNilai" 
                    wire:loading.attr="disabled"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                <span wire:loading.remove wire:target="simpanNilai">Simpan Nilai</span>
                <span wire:loading wire:target="simpanNilai">Menyimpan...</span>
            </button>
        </div>
    </div>
</div> 