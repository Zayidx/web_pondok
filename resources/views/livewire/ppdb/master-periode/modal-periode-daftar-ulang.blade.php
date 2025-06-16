<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
    <div class="sm:flex sm:items-start">
        <div class="w-full">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                {{ $periodeId ? 'Edit' : 'Tambah' }} Periode Daftar Ulang
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="nama_periode" class="block text-sm font-medium text-gray-700">Nama Periode</label>
                    <input type="text" wire:model="nama_periode" id="nama_periode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    @error('nama_periode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                    <input type="text" wire:model="tahun_ajaran" id="tahun_ajaran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    @error('tahun_ajaran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" wire:model="tanggal_mulai" id="tanggal_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    @error('tanggal_mulai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                    <input type="date" wire:model="tanggal_selesai" id="tanggal_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    @error('tanggal_selesai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-gray-300 text-primary focus:ring-primary">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Status Aktif</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
    <button wire:click="save" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
        Simpan
    </button>
    <button wire:click="$emit('closeModal')" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
        Batal
    </button>
</div> 