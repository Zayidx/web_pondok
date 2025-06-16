<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Status Pendaftaran</h2>
                
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-4 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700">
                        {{ session('info') }}
                    </div>
                @endif

                <!-- Student Info -->
                <div class="mb-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $santri->nama_lengkap }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NISN</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $santri->nisn }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $santri->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Program</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ ucfirst($santri->tipe_pendaftaran) }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Status Message -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Status</h3>
                    <div class="p-4 rounded-lg {{ $santri->status_santri === 'daftar_ulang' ? ($santri->status_pembayaran === 'verified' ? 'bg-green-100' : ($santri->status_pembayaran === 'rejected' ? 'bg-red-100' : 'bg-yellow-100')) : ($santri->status_santri === 'diterima' ? 'bg-green-100' : 'bg-gray-100') }}">
                        <p class="text-lg">{{ $statusMessage }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-end space-x-4">
                    @if($santri->status_santri === 'diterima')
                        <button wire:click="daftarUlang" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                            Lanjutkan ke Pendaftaran Ulang
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 