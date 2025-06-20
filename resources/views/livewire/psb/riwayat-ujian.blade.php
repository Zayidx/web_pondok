<style>
    .gradient-bg {
        background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
    }

    .card-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .hover-lift:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .table-row-hover:hover {
        background-color: #f9fafb;
    }
</style>

<div class="gradient-bg min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-8 hover-lift">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Riwayat Ujian</h1>
                    @if (Auth::guard('santri')->check())
                        <p class="text-lg text-gray-600">{{ Auth::guard('santri')->user()->nama_lengkap }} - {{ Auth::guard('santri')->user()->kelas ?? 'Calon Santri' }}</p>
                    @else
                        <p class="text-lg text-gray-600">User tidak terautentikasi</p>
                    @endif
                </div>
                <a href="{{ route('santri.dashboard-ujian') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium transition duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl card-shadow p-6 hover-lift">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats->total_ujian }}</p>
                        <p class="text-sm text-gray-600">Total Ujian</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6 hover-lift">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats->selesai }}</p>
                        <p class="text-sm text-gray-600">Selesai</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6 hover-lift">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats->menunggu }}</p>
                        <p class="text-sm text-gray-600">Menunggu Nilai</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6 hover-lift">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ round($stats->rata_rata) }}</p>
                        <p class="text-sm text-gray-600">Rata-rata Nilai</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4 justify-between">
                <div class="flex flex-col md:flex-row gap-4">
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                        <select id="subject" wire:model.live="mataPelajaran" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 py-2 px-3">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($mataPelajaranList as $mapel)
                            <option value="{{ $mapel }}">{{ $mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" wire:model.live="statusFilter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 py-2 px-3">
                            <option value="">Semua Status</option>
                            <option value="selesai">Selesai</option>
                            <option value="menunggu">Menunggu Nilai</option>
                        </select>
                    </div>
                </div>
                <div class="self-end">
                    <button wire:click="resetFilters" class="bg-blue-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-600 transition duration-300">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Exam History Table -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <div class="w-2 h-8 bg-blue-500 rounded-full"></div>
                Daftar Riwayat Ujian
            </h2>

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
                        <tr class="table-row-hover transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $hasil->ujian->mata_pelajaran }}</div>
                                <div class="text-sm text-gray-500">{{ $hasil->ujian->jenis_ujian ?? 'Ujian' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $hasil->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"></div>
                                <div class="text-sm text-gray-500"></div>
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
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada riwayat ujian</h3>
                                <p class="mt-1 text-sm text-gray-500">Riwayat ujian akan muncul setelah Anda mengerjakan ujian.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $hasilUjian->links() }}
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center">
            <button onclick="window.location.href='{{ route('santri.dashboard-ujian') }}'" class="px-8 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition duration-300 shadow-lg">
                Kembali ke Dashboard
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click animation to cards
        const cards = document.querySelectorAll('.hover-lift');
        cards.forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    });
</script>