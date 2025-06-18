<div>
    <div class="container mx-auto p-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Hasil Absensi: {{ $jadwal->mata_pelajaran }}</h1>
            <p class="text-xl text-gray-600">Kelas: {{ $jadwal->kelas->nama }} | {{ $tanggalFormatted }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
            <h2 class="text-xl font-bold mb-4">Daftar Kehadiran Santri</h2>
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
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
                                    @foreach(['Hadir', 'Izin', 'Sakit', 'Alpa'] as $status)
                                        <button 
                                            wire:click="updateStatus({{ $santri->id }}, '{{ $status }}')"
                                            class="px-2 py-1 text-xs rounded
                                                {{ ($statusKehadiran[$santri->id] ?? 'Alpa') == $status ? 
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
             <div class="mt-8 text-center">
                <a href="{{ route('admin.piket.detail_kelas', ['kelasId' => $jadwal->kelas_id, 'tanggal' => $tanggal]) }}" wire:navigate class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Kembali ke Detail Jadwal
                </a>
            </div>
        </div>
    </div>
</div>