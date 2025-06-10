            <!-- Periode Daftar Ulang -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Periode Daftar Ulang</h3>
                    <button wire:click="$emit('openModal', 'ppdb.master-periode.modal-periode-daftar-ulang')" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition duration-300">
                        Tambah Periode
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($periodeDaftarUlang as $periode)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $periode->nama_periode }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $periode->tahun_ajaran }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $periode->tanggal_mulai->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $periode->tanggal_selesai->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $periode->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $periode->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button wire:click="$emit('openModal', 'ppdb.master-periode.modal-periode-daftar-ulang', {{ json_encode(['periodeId' => $periode->id]) }})" class="text-primary hover:text-primary-dark mr-3">
                                            Edit
                                        </button>
                                        <button wire:click="deletePeriodeDaftarUlang({{ $periode->id }})" class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data periode daftar ulang
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div> 