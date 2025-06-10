<div>
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Total Pendaftar</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $totalPendaftar }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Menunggu Verifikasi</h3>
            <p class="text-3xl font-bold text-yellow-500">{{ $totalMenunggu }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Terverifikasi</h3>
            <p class="text-3xl font-bold text-green-500">{{ $totalDiverifikasi }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">Ditolak</h3>
            <p class="text-3xl font-bold text-red-500">{{ $totalDitolak }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Daftar Pendaftaran Ulang</h2>
                <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                    <!-- Search -->
                    <div class="relative flex-grow md:flex-grow-0">
                        <input wire:model.live="search" type="search" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Cari nama santri...">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <!-- Filter Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle w-full md:w-auto" type="button" data-bs-toggle="dropdown">
                            Filter Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" wire:click="filterByStatus('all')" href="#">Semua</a></li>
                            <li><a class="dropdown-item" wire:click="filterByStatus('pending')" href="#">Pending</a></li>
                            <li><a class="dropdown-item" wire:click="filterByStatus('verified')" href="#">Terverifikasi</a></li>
                            <li><a class="dropdown-item" wire:click="filterByStatus('rejected')" href="#">Ditolak</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Santri</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pendaftaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar Ulang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($registrations as $index => $registration)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $registration->santri->nama_lengkap }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $registration->no_pendaftaran }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $registration->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $registration->status === 'verified' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $registration->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $registration->status === 'pending' ? 'Menunggu' : '' }}
                                        {{ $registration->status === 'verified' ? 'Terverifikasi' : '' }}
                                        {{ $registration->status === 'rejected' ? 'Ditolak' : '' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="showDetail({{ $registration->id }})" 
                                            class="text-blue-600 hover:text-blue-900 mr-2">
                                        <i class="mdi mdi-eye text-lg"></i>
                                    </button>
                                    <button wire:click="verifyRegistration({{ $registration->id }})" 
                                            class="text-green-600 hover:text-green-900 mr-2">
                                        <i class="mdi mdi-check text-lg"></i>
                                    </button>
                                    <button wire:click="rejectRegistration({{ $registration->id }})" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="mdi mdi-close text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada data pendaftaran ulang
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    @if($selectedRegistration)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pendaftaran Ulang</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="mb-2"><strong>Nama:</strong> {{ $selectedRegistration->santri->nama_lengkap }}</p>
                            <p class="mb-2"><strong>No. Pendaftaran:</strong> {{ $selectedRegistration->no_pendaftaran }}</p>
                            <p class="mb-2"><strong>Tanggal Daftar:</strong> {{ $selectedRegistration->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="mb-2">
                                <strong>Status:</strong> 
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $selectedRegistration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $selectedRegistration->status === 'verified' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $selectedRegistration->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $selectedRegistration->status === 'pending' ? 'Menunggu' : '' }}
                                    {{ $selectedRegistration->status === 'verified' ? 'Terverifikasi' : '' }}
                                    {{ $selectedRegistration->status === 'rejected' ? 'Ditolak' : '' }}
                                </span>
                            </p>
                            @if($selectedRegistration->verified_at)
                            <p class="mb-2"><strong>Tanggal Verifikasi:</strong> {{ $selectedRegistration->verified_at->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
                <button wire:click="$set('showMessage', false)" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div> 