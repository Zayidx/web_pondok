<script src="https://cdn.tailwindcss.com"></script>

<div class="container mx-auto p-4 font-sans antialiased">
    @if ($kelas)
        <h1 class="text-3xl font-extrabold mb-2 text-gray-900 text-center">Detail Jadwal: {{ $kelas->nama }}</h1>
        <p class="text-xl text-gray-600 mb-8 text-center">{{ $tanggalFormatted }}</p>

        @if ($jadwalKelasHariIni->isNotEmpty())
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($jadwalKelasHariIni as $jadwal)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 whitespace-nowrap font-medium text-gray-800">{{ $jadwal->mata_pelajaran }}</td>
                                <td class="py-3 px-4 whitespace-nowrap text-gray-600">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <!-- LOGIKA KONDISIONAL UNTUK TOMBOL -->
                                    @if($isToday)
                                        <!-- Jika hari ini, tombol mengarah ke halaman Absensi QR -->
                                        <a href="{{ route('admin.piket.absensi.murid', ['jadwalId' => $jadwal->id]) }}" wire:navigate class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                            <i class="bi bi-qr-code-scan mr-2"></i> Absensi
                                        </a>
                                    @else
                                        <!-- Jika hari lain, tombol mengarah ke halaman Hasil Absensi -->
                                        <a href="{{ route('admin.piket.hasil.absensi', ['jadwalId' => $jadwal->id, 'tanggal' => $tanggal]) }}" wire:navigate class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-teal-600 hover:bg-teal-700">
                                            <i class="bi bi-card-checklist mr-2"></i> Hasil Absensi
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold text-lg">Informasi</p>
                <p>Tidak ada jadwal pelajaran ditemukan untuk kelas ini pada tanggal yang dipilih.</p>
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('admin.piket.dashboard') }}" wire:navigate class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Kembali ke Dashboard
            </a>
        </div>
    @else
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-lg" role="alert">
            <p class="font-bold">Error</p>
            <p>Data kelas tidak ditemukan.</p>
        </div>
    @endif
</div>
