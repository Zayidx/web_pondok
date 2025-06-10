<div>
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pendaftaran Ulang</h2>
            <div class="flex gap-4 mt-4 md:mt-0">
                <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." class="px-4 py-2 border rounded-lg">
                <select wire:model.live="status" class="px-4 py-2 border rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="verified">Terverifikasi</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Santri</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Transfer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendaftaran as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->siswa->name }}</div>
                                <div class="text-sm text-gray-500">{{ $item->siswa->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->tanggal_transfer->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Rp {{ number_format($item->nominal_transfer, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->bank_pengirim }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $item->status === 'verified' ? 'bg-green-100 text-green-800' : 
                                       ($item->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="$emit('openModal', 'ppdb.modal-detail-pendaftaran-ulang', {{ json_encode(['pendaftaranId' => $item->id]) }})" 
                                    class="text-primary hover:text-primary-dark mr-3">
                                    Detail
                                </button>
                                @if($item->status === 'pending')
                                    <button wire:click="verifikasiPendaftaran({{ $item->id }})" 
                                        class="text-green-600 hover:text-green-900 mr-3">
                                        Verifikasi
                                    </button>
                                    <button wire:click="$emit('openModal', 'ppdb.modal-tolak-pendaftaran', {{ json_encode(['pendaftaranId' => $item->id]) }})" 
                                        class="text-red-600 hover:text-red-900">
                                        Tolak
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data pendaftaran ulang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pendaftaran->links() }}
        </div>
    </div>
</div> 