<div>
    <div class="gradient-bg min-h-screen">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="bg-white rounded-xl card-shadow p-6 mb-8 hover-lift">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $ujian->nama_ujian }}</h1>
                        <p class="text-lg text-gray-600">{{ $santri->nama_lengkap }}</p>
                    </div>

                    {{-- Penjelasan Timer: 
                        - `wire:poll.1s="tick"`: Ini adalah perintah Livewire untuk memanggil metode `tick()` di backend setiap 1 detik.
                        - `$this->waktuMundurFormatted`: Menampilkan sisa waktu yang sudah diformat dari backend (HH:MM:SS).
                    --}}
                    <div class="bg-red-100 px-4 py-2 rounded-lg" wire:poll.1s="tick">
                        <div class="text-center">
                            <div class="text-xl font-bold text-red-700 tabular-nums">
                                {{ $this->waktuMundurFormatted }}
                            </div>
                            <p class="text-red-600 text-sm">Sisa Waktu</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl card-shadow p-6 mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress Ujian</span>
                    <span class="text-sm text-gray-500">{{ $soalDijawab }} dari {{ $jumlahSoal }} soal</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    {{-- Penjelasan Progress: Lebar (width) dari div ini dihitung secara dinamis berdasarkan persentase soal yang sudah dijawab. --}}
                    <div class="bg-primary h-2 rounded-full transition-all duration-300"
                        style="width: {{ $jumlahSoal > 0 ? ($soalDijawab / $jumlahSoal) * 100 : 0 }}%">
                    </div>
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

                    <div class="bg-white rounded-lg card-shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Navigasi Soal</h3>
                        <div class="grid grid-cols-5 gap-2" wire:key="navigasi-soal">
                            {{-- Penjelasan Navigasi:
                                - Looping sebanyak jumlah soal.
                                - Warna tombol berubah secara dinamis: biru untuk soal aktif, hijau untuk soal terjawab, abu-abu untuk soal belum dijawab.
                            --}}
                            @if($soals)
                                @foreach($soals as $index => $soal)
                                    <button wire:click="gotoPage({{ $index + 1 }})"
                                        class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-medium
                                        {{ ($index + 1) == $currentPage ? 'bg-blue-600 text-white' :
                                           (isset($jawabanSiswa[$soal->id]) && !empty($jawabanSiswa[$soal->id]) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600') }}"
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
                                    
                                    {{-- Penjelasan Blok Soal:
                                        - Menggunakan @if untuk menampilkan format yang berbeda untuk soal Pilihan Ganda (pg) dan Essay.
                                    --}}
                                    @if ($currentSoal->tipe_soal === 'pg')
                                        <div class="space-y-3" wire:key="soal-{{ $currentSoal->id }}-pg">
                                            {{-- Penjelasan Perulangan Opsi:
                                                - Kode @foreach yang hilang kini sudah dikembalikan.
                                                - Looping ini akan menampilkan semua opsi jawaban untuk soal saat ini.
                                            --}}
                                            @foreach ($currentSoal->opsi as $key => $opsi)
                                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-600 hover:bg-blue-50 cursor-pointer transition duration-200 group" 
                                                       wire:key="opsi-{{ $currentSoal->id }}-{{ $key }}">
                                                    
                                                    {{-- Penjelasan Input Radio (FIX PALING PENTING):
                                                        - `wire:model` TELAH DIHAPUS untuk menghilangkan konflik.
                                                        - `x-on:click`: Satu-satunya yang bertanggung jawab memanggil backend.
                                                        - Logika `if-else` di dalamnya: jika radio yang sama diklik lagi, panggil `hapusJawaban`. Jika radio baru diklik, panggil `simpanJawaban`.
                                                        - `{{ ... ? 'checked' : '' }}`: Untuk memastikan pilihan yang sudah disimpan tetap ter-centang saat pindah soal.
                                                    --}}
                                                    <input type="radio"
                                                           name="question_{{ $currentSoal->id }}"
                                                           value="{{ $key }}"
                                                           class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-200 focus:ring-2"
                                                           x-data="{ soalId: {{ $currentSoal->id }}, key: '{{ $key }}' }"
                                                           x-on:click="if ($el.checked && $el.value === @js($jawabanSiswa[$currentSoal->id] ?? '')) { 
                                                               $el.checked = false; 
                                                               @this.call('hapusJawaban', soalId); 
                                                           } else { 
                                                               @this.call('simpanJawaban', soalId, key); 
                                                           }"
                                                           {{ !empty($jawabanSiswa[$currentSoal->id]) && $jawabanSiswa[$currentSoal->id] === $key ? 'checked' : '' }}>
                                                    
                                                    <div class="ml-4 flex-1">
                                                        <span class="text-gray-800 group-hover:text-blue-600">
                                                            <span class="font-semibold mr-2">{{ $key }}.</span>
                                                            {{ $opsi['teks'] }}
                                                        </span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="space-y-3" wire:key="soal-{{ $currentSoal->id }}-esai">
                                            {{-- Penjelasan Textarea Essay:
                                                - `wire:model.lazy` dan `wire:change`: Kombinasi ini menyimpan jawaban essay saat pengguna selesai mengetik dan keluar dari kotak teks, lebih efisien daripada menyimpan per huruf.
                                            --}}
                                            <textarea wire:model.lazy="jawabanSiswa.{{ $currentSoal->id }}"
                                                      wire:change="simpanJawaban({{ $currentSoal->id }}, $event.target.value)"
                                                      class="w-full p-4 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-200 focus:ring-2 resize-none transition duration-200"
                                                      rows="4"
                                                      placeholder="Tulis jawaban essay di sini..."></textarea>
                                        </div>
                                    @endif

                                    <div class="flex justify-between items-center mt-6 w-full">
                                        <button wire:click="previousPage" @if($currentPage == 1) disabled @endif class="px-4 py-2 flex items-center gap-2 bg-gray-100 text-gray-600 rounded-lg">Sebelumnya</button>
                                        <button wire:click="nextPage" @if($currentPage == $jumlahSoal) disabled @endif class="px-4 py-2 flex items-center gap-2 bg-blue-600 text-white rounded-lg">Selanjutnya</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" wire:click="confirmSubmit" class="inline-flex items-center px-6 py-3 text-lg font-medium text-white bg-green-600 rounded-lg">
                        <span>Selesai & Kumpulkan</span>
                    </button>
                    @else
                    <div class="bg-white rounded-xl card-shadow p-6">
                        <p class="text-gray-600">Tidak ada soal yang tersedia.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

     <!-- Confirmation Modal -->
     <div id="confirmation-modal" class="modal-container" wire:ignore>
        <div class="modal-backdrop" onclick="hideModal()"></div>
        <div class="modal-content">
            <div class="text-center">
                <!-- Warning Icon -->
                <div class="modal-icon">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="modal-title">Konfirmasi Pengumpulan</h3>
                <p class="modal-message">{{ $modalMessage }}</p>

                <div class="modal-buttons">
                    <button type="button" onclick="hideModal()" class="modal-button modal-button-cancel">
                        Kembali
                    </button>
                    <button type="button" wire:click="submitUjian" onclick="hideModal()" class="modal-button modal-button-confirm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Ya, Kumpulkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Penjelasan Javascript:
        - `show/hideModal`: Fungsi untuk menampilkan dan menyembunyikan modal konfirmasi.
        - `window.addEventListener`: Mendengarkan event dari backend Livewire untuk memicu fungsi `show/hideModal`.
        - `Livewire.on('jawaban-updated', ...)`: Event listener untuk menghapus centang pada radio button jika jawaban dihapus.
    --}}
   @push('scripts')
   <script>
       function showModal() {
           const modal = document.getElementById('confirmation-modal');
           if (modal) {
               modal.style.display = 'flex';
               modal.classList.add('modal-visible');
           }
       }
       function hideModal() {
           const modal = document.getElementById('confirmation-modal');
           if (modal) {
               modal.style.display = 'none';
               modal.classList.remove('modal-visible');
           }
       }
       document.addEventListener('DOMContentLoaded', () => { hideModal(); });
       window.addEventListener('show-modal', () => { showModal(); });
       window.addEventListener('hide-modal', () => { hideModal(); });
       window.addEventListener('livewire:load', () => {
           Livewire.on('jawaban-updated', ({ soalId }) => {
               document.querySelectorAll(`input[name='question_${soalId}']`).forEach(input => {
                   input.checked = false;
               });
           });
       });
   </script>
   @endpush
</div>