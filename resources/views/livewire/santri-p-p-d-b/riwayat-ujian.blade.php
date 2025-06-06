<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl card-shadow p-6 mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Riwayat Ujian</h1>

        <!-- Exam History Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mata Pelajaran
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Waktu
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nilai
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($hasilUjian as $hasil)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $hasil->ujian->mata_pelajaran }}</div>
                            <div class="text-sm text-gray-500">{{ $hasil->ujian->jenis_ujian }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $hasil->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $hasil->waktu_mulai->format('H:i') }} - {{ $hasil->waktu_selesai->format('H:i') }}</div>
                            <div class="text-sm text-gray-500">{{ $hasil->waktu_mulai->diffInMinutes($hasil->waktu_selesai) }} menit</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($hasil->status === 'selesai')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            @elseif($hasil->status === 'menunggu')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Menunggu Nilai
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($hasil->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($hasil->nilai)
                                <div class="text-sm font-medium text-gray-900">{{ $hasil->nilai }}</div>
                            @else
                                <div class="text-sm text-gray-500">-</div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            Belum ada riwayat ujian
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $hasilUjian->links() }}
        </div>
    </div>
</div> 