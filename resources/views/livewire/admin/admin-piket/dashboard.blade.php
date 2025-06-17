<script src="https://cdn.tailwindcss.com"></script>
<div>
    <div class="container mx-auto p-4 font-sans antialiased">
        <h1 class="text-3xl font-extrabold mb-4 text-gray-900 text-center">Dashboard Admin Piket</h1>

        <!-- Kontrol untuk memilih tanggal dan ekspor -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <label for="date-filter" class="font-semibold text-gray-700">Pilih Tanggal:</label>
                <!-- Input tanggal yang terhubung dengan properti $selectedDate -->
                <input type="date" id="date-filter" wire:model.live="selectedDate" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <!-- Tombol untuk mengekspor data ke Excel -->
            <button wire:click="exportExcel" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="bi bi-file-earmark-excel-fill mr-2"></i>
                Export ke Excel
            </button>
        </div>

        <p class="text-xl text-gray-700 mb-8 text-center">
            Menampilkan Jadwal untuk: <span class="font-semibold text-blue-700">{{ $hariDipilih }}, {{ $tanggalDipilihFormatted }}</span>
        </p>

        @if (!empty($groupedJadwal))
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Mapel</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal Masuk</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal Pulang</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($groupedJadwal as $kelasId => $dataKelas)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 whitespace-nowrap font-semibold">{{ $dataKelas['kelas_nama'] }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $dataKelas['total_mapel'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">{{ $dataKelas['jadwal_masuk'] }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">{{ $dataKelas['jadwal_pulang'] }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <!-- Link detail sekarang menyertakan tanggal yang dipilih -->
                                    <a href="{{ route('admin.piket.detail_kelas', ['kelasId' => $dataKelas['kelas_id'], 'tanggal' => $selectedDate]) }}" wire:navigate class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                        <i class="bi bi-eye-fill mr-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 px-4 text-center text-gray-500">Tidak ada jadwal pelajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold text-lg">Informasi</p>
                <p>Tidak ada jadwal pelajaran untuk <span class="font-semibold">{{ $hariDipilih }}, {{ $tanggalDipilihFormatted }}</span>.</p>
            </div>
        @endif
    </div>
</div>
