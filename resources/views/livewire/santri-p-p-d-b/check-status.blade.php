                <!-- Timeline Item: Ujian -->
                <div class="relative">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $siswa->status === 'sedang_ujian' ? 'bg-blue-500' : ($siswa->status === 'selesai_ujian' || $siswa->status === 'daftar_ulang' ? 'bg-green-500' : 'bg-gray-300') }}">
                                <i class="fas fa-pencil-alt text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Ujian</h3>
                            <p class="text-sm text-gray-500">Mengerjakan soal ujian</p>
                        </div>
                    </div>
                    @if($siswa->status === 'sedang_ujian')
                        <a href="{{ route('santri.ujian') }}" class="mt-2 ml-14 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Mulai Ujian
                        </a>
                    @endif
                </div>

                <!-- Timeline Item: Daftar Ulang -->
                <div class="relative">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $siswa->status === 'daftar_ulang' ? 'bg-blue-500' : ($siswa->status === 'selesai_daftar_ulang' ? 'bg-green-500' : 'bg-gray-300') }}">
                                <i class="fas fa-clipboard-check text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Daftar Ulang</h3>
                            <p class="text-sm text-gray-500">Melengkapi proses pendaftaran ulang</p>
                        </div>
                    </div>
                    @if($siswa->status === 'daftar_ulang')
                        <a href="{{ route('santri.daftar-ulang') }}" class="mt-2 ml-14 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Daftar Ulang
                        </a>
                    @endif
                </div> 