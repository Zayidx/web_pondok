<div>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.js"></script>
    <div class="container mx-auto p-4" x-data="{ qrUrl: @entangle('qrCodeUrl'), expires: @entangle('sessionExpiresAt'), countdown: '' }"
         x-init="
            function updateCountdown() {
                if (!expires) {
                    countdown = '';
                    return;
                }
                const now = new Date();
                const expiryDate = new Date(expires);
                const diff = expiryDate.getTime() - now.getTime();
                if (diff <= 0) {
                    countdown = 'Kedaluwarsa';
                    $wire.qrCodeUrl = null; // Menghilangkan QR dari view
                    return;
                }
                const minutes = Math.floor(diff / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                countdown = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            setInterval(updateCountdown, 1000);

            $watch('qrUrl', value => {
                if (value) {
                    const qrCodeContainer = document.getElementById('qrcode');
                    qrCodeContainer.innerHTML = '';
                    let qr = qrcode(0, 'M');
                    qr.addData(value);
                    qr.make();
                    qrCodeContainer.innerHTML = qr.createImgTag(8, 8);
                }
            });
         ">

        <!-- Header Halaman -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Absensi: {{ $jadwal->mata_pelajaran }}</h1>
            <p class="text-xl text-gray-600">Kelas: {{ $jadwal->kelas->nama }} | {{ now()->translatedFormat('d F Y') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Kolom QR Code & Live Scan -->
            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4 text-center">QR Code Absensi</h2>

                <!-- Area untuk menampilkan QR Code -->
                <div id="qr-code-display" class="flex justify-center items-center flex-col">
                    <template x-if="qrUrl">
                        <div class="p-4 border rounded-lg">
                            <div id="qrcode"></div>
                            <p class="text-center text-red-500 font-semibold mt-2" x-text="`Kedaluwarsa dalam: ${countdown}`"></p>
                        </div>
                    </template>

                    <!-- Tombol untuk membuat QR Code -->
                    <template x-if="!qrUrl">
                        <button wire:click="generateNewQrCode" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            <i class="bi bi-qr-code mr-2"></i> Buat QR Code
                        </button>
                    </template>
                </div>

                <hr class="my-6">

                <!-- Tabel Live Scan -->
                <h3 class="text-lg font-bold mb-2">Santri yang Telah Scan</h3>
                <div class="h-64 overflow-y-auto" wire:poll.5s="checkScanStatus">
                    <table class="w-full text-sm">
                        <tbody>
                            @forelse($liveScans as $log)
                                <tr class="border-b">
                                    <td class="py-2">{{ $log->santri->nama }}</td>
                                    <td class="py-2 text-right text-green-600 font-semibold">{{ $log->created_at->format('H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-2 text-gray-500 italic">Belum ada santri yang scan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kolom Daftar Hadir Lengkap -->
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Daftar Kehadiran Santri</h2>
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">No</th>
                                <th class="p-2 text-left">Nama Santri</th>
                                <th class="p-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($semuaSantri as $index => $santri)
                                <tr class="border-b">
                                    <td class="p-2">{{ $index + 1 }}</td>
                                    <td class="p-2">{{ $santri->nama }}</td>
                                    <td class="p-2 text-center">
                                        <!-- Opsi untuk mengubah status secara manual -->
                                        @foreach(['Hadir', 'Izin', 'Sakit', 'Alpa'] as $status)
                                            <button 
                                                wire:click="updateStatus({{ $santri->id }}, '{{ $status }}')"
                                                class="px-2 py-1 text-xs rounded
                                                    {{ $statusKehadiran[$santri->id] == $status ? 
                                                        ($status == 'Hadir' ? 'bg-green-500 text-white' : 
                                                        ($status == 'Izin' || $status == 'Sakit' ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white')) : 
                                                        'bg-gray-200 text-gray-700' }}">
                                                {{ $status }}
                                            </button>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
