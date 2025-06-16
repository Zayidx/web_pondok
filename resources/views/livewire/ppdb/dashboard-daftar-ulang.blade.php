<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pendaftaran Ulang</h1>
            <p class="mt-2 text-gray-600">Silakan lengkapi proses pendaftaran ulang dengan mengunggah bukti pembayaran</p>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <!-- Success Message -->
                @if($successMessage)
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
                        {{ $successMessage }}
                    </div>
                @endif

                <!-- Error Message -->
                @if($errorMessage)
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                        {{ $errorMessage }}
                    </div>
                @endif

                <!-- Data Santri -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Data Santri</h2>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $santri->nama_lengkap }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NISN</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $santri->nisn }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Program</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ ucfirst($santri->tipe_pendaftaran) }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Rincian Biaya -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Rincian Biaya</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left py-2">Jenis Biaya</th>
                                    <th class="text-right py-2">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rincianBiaya as $biaya)
                                    <tr>
                                        <td class="py-2">{{ $biaya->nama_biaya }}</td>
                                        <td class="text-right py-2">Rp {{ number_format($biaya->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold border-t">
                                    <td class="py-2">Total</td>
                                    <td class="text-right py-2">Rp {{ number_format($rincianBiaya->sum('jumlah'), 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Informasi Rekening -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Rekening Pembayaran</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($rekeningList as $rekening)
                            <div class="border rounded-lg p-4">
                                <p class="font-semibold">{{ $rekening->nama_bank }}</p>
                                <p class="text-lg">{{ $rekening->nomor_rekening }}</p>
                                <p class="text-gray-600">a.n. {{ $rekening->atas_nama }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Form Upload Bukti Pembayaran -->
                <form wire:submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Pengirim *</label>
                        <input type="text" wire:model="bankPengirim" class="w-full px-4 py-2 border rounded-lg" placeholder="Nama Bank Pengirim">
                        @error('bankPengirim') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim *</label>
                        <input type="text" wire:model="namaPengirim" class="w-full px-4 py-2 border rounded-lg" placeholder="Nama Pemilik Rekening">
                        @error('namaPengirim') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembayaran *</label>
                        <input type="date" wire:model="tanggalPembayaran" class="w-full px-4 py-2 border rounded-lg">
                        @error('tanggalPembayaran') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nominal Pembayaran *</label>
                        <input type="number" wire:model="nominalPembayaran" class="w-full px-4 py-2 border rounded-lg" placeholder="0">
                        @error('nominalPembayaran') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran *</label>
                        <input type="file" wire:model="buktiPembayaran" class="w-full" accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG (Max. 2MB)</p>
                        @error('buktiPembayaran') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            Upload Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 