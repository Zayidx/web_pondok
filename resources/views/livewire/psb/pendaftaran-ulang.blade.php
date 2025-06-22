<div class="">
    <div>
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center">
                            <img class="h-10 w-10" src="logo.webp" alt="Logo SMA" />
                            <div class="ml-3">
                                <h1 class="text-xl font-bold text-primary">SMA Bina Prestasi</h1>
                            </div>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="https://wa.me/6285156156851" target="_blank" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">
                            <i class="fas fa-whatsapp mr-2"></i>Bantuan
                        </a>
                        <a href="{{ route('check-status') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition duration-300">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <section class="bg-gradient-to-r from-primary to-blue-700 text-white py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">Pendaftaran Ulang Santri</h1>
                {{-- Menampilkan tahun ajaran jika periode aktif, jika tidak, tampilkan fallback --}}
                <p class="text-xl mb-6">Tahun Ajaran {{ $periode_daftar_ulang->tahun_ajaran ?? '2025/2026' }}</p>
            </div>
        </section>

        <section class="py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- ====================================================================== --}}
                {{-- AWAL BLOK KONDISIONAL UTAMA YANG TELAH DIPERBAIKI --}}
                {{-- ====================================================================== --}}

                {{-- KONDISI 1: Jika pembayaran sudah 'verified' atau 'pending', tampilkan statusnya. --}}
                @if(in_array($santri->status_pembayaran, ['verified', 'pending']))
                    
                    {{-- Sub-kondisi untuk status VERIFIED --}}
                    @if ($santri->status_pembayaran === 'verified')
                        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                            <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Pendaftaran Ulang Telah Diverifikasi</h2>
                            <p class="text-gray-600 mb-6">
                                Selamat! Pembayaran Anda telah kami terima dan pendaftaran ulang Anda telah selesai.
                            </p>
                            <a href="{{ route('check-status') }}" class="inline-block px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition duration-300">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Halaman Status
                            </a>
                        </div>
                    @endif

                    {{-- Sub-kondisi untuk status PENDING --}}
                    @if ($santri->status_pembayaran === 'pending')
                        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-hourglass-half text-blue-600 text-3xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Menunggu Verifikasi Pembayaran</h2>
                            <p class="text-gray-600 mb-6">
                                Terima kasih telah melakukan pendaftaran ulang. Bukti pembayaran Anda sedang kami periksa. Mohon tunggu informasi selanjutnya.
                            </p>
                            <a href="{{ route('check-status') }}" class="inline-block px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition duration-300">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Halaman Status
                            </a>
                        </div>
                    @endif

                {{-- KONDISI 2: Jika periode tidak ditemukan (null), tampilkan pesan bahwa pendaftaran belum dibuka. --}}
                @elseif (!$periode_daftar_ulang || !$pengaturan)
                    <div class="bg-white rounded-lg shadow-lg p-8 text-center my-20">
                        <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clock text-yellow-600 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Pendaftaran Ulang Belum Dibuka</h2>
                        <p class="text-gray-600 mb-6">
                            Saat ini belum ada periode pendaftaran ulang yang aktif. Silakan periksa kembali halaman status Anda secara berkala untuk informasi terbaru.
                        </p>
                        <a href="{{ route('check-status') }}" class="inline-block px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Halaman Status
                        </a>
                    </div>

                {{-- KONDISI 3: Jika semua syarat terpenuhi (periode aktif dan belum bayar), tampilkan informasi dan form. --}}
                @else
                    
                    <section class="bg-white py-8 border-b mb-8">
                        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="flex items-center justify-center space-x-4 md:space-x-8">
                                <div class="flex items-center">
                                    <div class="{{ $formPage == 1 ? 'bg-primary text-white' : 'bg-gray-300 text-gray-600' }} rounded-full w-10 h-10 flex items-center justify-center font-bold">1</div>
                                    <span class="ml-2 {{ $formPage == 1 ? 'text-primary font-semibold' : 'text-gray-600' }}">Data Pembayaran</span>
                                </div>
                                <div class="w-8 h-1 bg-gray-300"></div>
                                <div class="flex items-center">
                                    <div class="{{ $formPage == 2 ? 'bg-primary text-white' : 'bg-gray-300 text-gray-600' }} rounded-full w-10 h-10 flex items-center justify-center font-bold">2</div>
                                    <span class="ml-2 {{ $formPage == 2 ? 'text-primary font-semibold' : 'text-gray-600' }}">Upload Bukti</span>
                                </div>
                                <div class="w-8 h-1 bg-gray-300"></div>
                                <div class="flex items-center">
                                    <div class="{{ $formPage == 3 ? 'bg-primary text-white' : 'bg-gray-300 text-gray-600' }} rounded-full w-10 h-10 flex items-center justify-center font-bold">3</div>
                                    <span class="ml-2 {{ $formPage == 3 ? 'text-primary font-semibold' : 'text-gray-600' }}">Konfirmasi</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="bg-white rounded-xl card-shadow p-6 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                            Informasi Pendaftaran Ulang
                        </h2>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-blue-800 mb-2">Biaya Pendaftaran Ulang</h3>
                                    <div class="space-y-2 text-sm text-blue-700">
                                        @foreach($biayas as $biaya)
                                            <div class="flex justify-between">
                                                <span>{{ $biaya->nama_biaya }}</span>
                                                <span class="font-medium">Rp {{ number_format($biaya->jumlah, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                        <hr class="border-blue-300">
                                        <div class="flex justify-between font-bold text-blue-800">
                                            <span>Total</span>
                                            <span>Rp {{ number_format($total_biaya, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-green-800 mb-2">Informasi Rekening</h3>
                                    <div class="space-y-2 text-sm text-green-700">
                                        <div><span class="font-medium">Nama Bank:</span> {{ $pengaturan->nama_bank }}</div>
                                        <div><span class="font-medium">No. Rekening:</span> {{ $pengaturan->nomor_rekening }}</div>
                                        <div><span class="font-medium">Atas Nama:</span> {{ $pengaturan->atas_nama }}</div>
                                        <div class="mt-3 p-2 bg-green-100 rounded text-xs">
                                            <strong>Catatan:</strong> {{ $pengaturan->catatan_transfer }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h3 class="font-semibold text-yellow-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                Batas Waktu Pendaftaran
                            </h3>
                            <p class="text-yellow-700 text-sm">
                                Pendaftaran ulang harus diselesaikan paling lambat <strong>{{ \Carbon\Carbon::parse($periode_daftar_ulang->periode_selesai)->format('d F Y') }}</strong>.
                                Setelah batas waktu tersebut, santri yang belum melakukan pendaftaran ulang akan dianggap mengundurkan diri.
                            </p>
                        </div>
                    </div>

                    <form wire:submit.prevent="submit" id="registrationForm" class="space-y-8">
                        <div id="step1" class="{{ $formPage == 1 ? 'block' : 'hidden' }} bg-white rounded-lg shadow-lg p-8">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Data Pembayaran</h2>
                                <p class="text-gray-600">Lengkapi data pembayaran dengan benar</p>
                            </div>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nominal Transfer *</label>
                                    <input type="number" wire:model.lazy="pembayaranForm.nominal_pembayaran" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Masukkan nominal transfer" />
                                    @error('pembayaranForm.nominal_pembayaran') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transfer *</label>
                                    <input type="date" wire:model.lazy="pembayaranForm.tanggal_pembayaran" class="w-full px-4 py-3 border border-gray-300 rounded-lg" />
                                    @error('pembayaranForm.tanggal_pembayaran') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Pengirim *</label>
                                    <select wire:model.lazy="pembayaranForm.bank_pengirim" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        <option value="">Pilih Bank</option>
                                        <option value="BCA">BCA</option>
                                        <option value="BNI">BNI</option>
                                        <option value="BRI">BRI</option>
                                        <option value="Mandiri">Mandiri</option>
                                        <option value="BSI">BSI</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    @error('pembayaranForm.bank_pengirim') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim *</label>
                                    <input type="text" wire:model.lazy="pembayaranForm.nama_pengirim" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Masukkan nama pengirim" />
                                    @error('pembayaranForm.nama_pengirim') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="flex justify-end mt-8">
                                <button type="button" wire:click="nextStep" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold">
                                    Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                        <div id="step2" class="{{ $formPage == 2 ? 'block' : 'hidden' }} bg-white rounded-lg shadow-lg p-8">
                           <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Upload Bukti Transfer</h2>
                                <p class="text-gray-600">Upload bukti transfer pembayaran daftar ulang (Max. 2MB)</p>
                            </div>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                <div class="text-center">
                                    <i class="fas fa-receipt text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Bukti Transfer *</h3>
                                    <input type="file" wire:model="bukti_pembayaran" accept="image/*" class="hidden" id="bukti_pembayaran" />
                                    <label for="bukti_pembayaran" class="bg-primary text-white px-6 py-2 rounded-lg cursor-pointer">Pilih File</label>
                                    <div class="mt-4" wire:loading wire:target="bukti_pembayaran">Mengunggah...</div>
                                    <div id="bukti_pembayaran_preview" class="mt-4 {{ $bukti_pembayaran ? 'block' : 'hidden' }}">
                                        @if ($bukti_pembayaran && !$errors->has('bukti_pembayaran'))
                                            <img src="{{ $bukti_pembayaran->temporaryUrl() }}" class="mx-auto h-48 w-auto mb-4 rounded-lg border">
                                            <p class="text-sm text-green-600">✓ File berhasil dipilih: {{ $bukti_pembayaran->getClientOriginalName() }}</p>
                                        @endif
                                    </div>
                                    @error('bukti_pembayaran') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="flex justify-between mt-8">
                                <button type="button" wire:click="previousStep" class="bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold">
                                    <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                                </button>
                                <button type="button" wire:click="nextStep" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold">
                                    Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                        <div id="step3" class="{{ $formPage == 3 ? 'block' : 'hidden' }} bg-white rounded-lg shadow-lg p-8">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Konfirmasi Data</h2>
                                <p class="text-gray-600">Periksa kembali data yang telah dimasukkan</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg mb-6 border">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div><p class="text-sm text-gray-500">Nominal Transfer</p><p class="font-medium">Rp {{ number_format((float)($pembayaranForm['nominal_pembayaran'] ?? 0), 0, ',', '.') }}</p></div>
                                    <div><p class="text-sm text-gray-500">Tanggal Transfer</p><p class="font-medium">{{ \Carbon\Carbon::parse($pembayaranForm['tanggal_pembayaran'])->format('d F Y') }}</p></div>
                                    <div><p class="text-sm text-gray-500">Bank Pengirim</p><p class="font-medium">{{ $pembayaranForm['bank_pengirim'] }}</p></div>
                                    <div><p class="text-sm text-gray-500">Nama Pengirim</p><p class="font-medium">{{ $pembayaranForm['nama_pengirim'] }}</p></div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                                <label class="flex items-start">
                                    <input type="checkbox" wire:model="terms" class="mt-1 text-primary focus:ring-primary" />
                                    <span class="ml-3 text-sm text-gray-700">Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan.</span>
                                </label>
                                @error('terms') <span class="text-red-600 text-sm block mt-2">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex justify-between mt-8">
                                <button type="button" wire:click="previousStep" class="bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold">
                                    <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                                </button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold disabled:opacity-50">
                                    <span wire:loading.remove wire:target="submit"><i class="fas fa-paper-plane mr-2"></i> Kirim Pendaftaran</span>
                                    <span wire:loading wire:target="submit"><i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                @endif

                <div wire:loading wire:target="submit" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="text-center text-white"><i class="fas fa-spinner fa-spin text-4xl"></i><p class="mt-2">Memproses...</p></div>
                </div>

                <div class="fixed bottom-4 right-4 z-50">
                    @if (session()->has('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <footer class="bg-gray-900 text-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex items-center justify-center mb-4">
                    <img class="h-8 w-8 mr-3" src="logo.webp" alt="Logo SMA" />
                    <h3 class="text-lg font-semibold">SMA Bina Prestasi</h3>
                </div>
                <p class="text-gray-400 mb-4">Jl. Pendidikan No. 123, Jakarta Selatan 12345</p>
                <p class="text-gray-400 text-sm">© 2025 SMA Bina Prestasi. All Rights Reserved.</p>
            </div>
        </footer>

        @if($showSuccessModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 max-w-md mx-4 text-center">
                <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pendaftaran Ulang Berhasil!</h3>
                <p class="text-gray-600 mb-6">
                    Data pembayaran Anda telah berhasil dikirim dan akan segera kami verifikasi.
                </p>
                <button wire:click="closeModal" class="bg-primary text-white px-6 py-2 rounded-lg">Kembali ke Status</button>
            </div>
        </div>
        @endif

    </div>
</div>