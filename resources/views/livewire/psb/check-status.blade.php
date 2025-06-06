<div class="min-h-screen bg-gradient-to-br from-primary to-primary-dark py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Check Status PPDB</h2>
            <p class="mt-2 text-sm text-gray-600">Masukkan NISN untuk melihat status pendaftaran</p>
        </div>

        <form wire:submit.prevent="checkStatus" class="space-y-6">
            <div>
                <label for="nisn" class="block text-sm font-medium text-gray-700">NISN</label>
                <div class="mt-1">
                    <input wire:model="nisn" type="text" name="nisn" id="nisn" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm" placeholder="Masukkan NISN">
                </div>
                @error('nisn') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Check Status
                </button>
            </div>
        </form>

        @if($message)
        <div class="mt-4 p-4 rounded-md bg-red-50 border border-red-200">
            <p class="text-sm text-red-600">{{ $message }}</p>
        </div>
        @endif

        @if($santri)
        <div class="mt-6 border-t border-gray-200 pt-6">
            <h3 class="text-lg font-medium text-gray-900">Informasi Pendaftaran</h3>
            <dl class="mt-4 space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $santri->nama_lengkap }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">NISN</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $santri->nisn }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        @if($santri->status_santri === 'sedang_ujian')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Sedang Ujian
                        </span>
                        <div class="mt-4">
                            <button wire:click="goToDashboard" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                Lanjutkan ke Dashboard Ujian
                            </button>
                        </div>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ ucwords(str_replace('_', ' ', $santri->status_santri)) }}
                        </span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
        @endif
    </div>
</div> 