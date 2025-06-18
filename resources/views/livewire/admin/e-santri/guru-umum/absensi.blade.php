<div class="container mx-auto px-4 py-8">
        {{-- Header Halaman --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

        {{-- Bagian Statistik Cepat (Cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Card Total Santri --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="bg-blue-500 p-3 rounded-full text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.122-1.28-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.122-1.28.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500">Total Santri</p>
                        <p class="text-2xl font-bold">150</p>
                    </div>
                </div>
            </div>
            {{-- Card Kehadiran Hari Ini --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="bg-green-500 p-3 rounded-full text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500">Hadir Hari Ini</p>
                        <p class="text-2xl font-bold">135</p>
                    </div>
                </div>
            </div>
            {{-- Card Izin/Sakit --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="bg-yellow-500 p-3 rounded-full text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500">Izin / Sakit</p>
                        <p class="text-2xl font-bold">10</p>
                    </div>
                </div>
            </div>
            {{-- Card Alfa --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="bg-red-500 p-3 rounded-full text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500">Alfa</p>
                        <p class="text-2xl font-bold">5</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bagian Generate QR Code --}}
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-700 mb-6">Generate / Regenerate QR Code Absensi</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                {{-- Pilihan Kelas --}}
                <div class="col-span-1">
                    <label for="kelas" class="block text-sm font-medium text-gray-700">Pilih Kelas</label>
                    <select id="kelas" name="kelas" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option>Kelas 7A</option>
                        <option>Kelas 7B</option>
                        <option>Kelas 8A</option>
                    </select>
                </div>
                {{-- Pilihan Mata Pelajaran --}}
                <div class="col-span-1">
                    <label for="mapel" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                    <select id="mapel" name="mapel" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option>Fiqih</option>
                        <option>Aqidah</option>
                        <option>Tahfidz</option>
                    </select>
                </div>
                {{-- Pilihan Jam Pelajaran --}}
                 <div class="col-span-1">
                    <label for="jam" class="block text-sm font-medium text-gray-700">Jam Ke</label>
                    <select id="jam" name="jam" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option>08:00 - 09:30</option>
                        <option>10:00 - 11:30</option>
                    </select>
                </div>
                {{-- Tombol Generate --}}
                <div class="col-span-1">
                     <button type="button" class="w-full inline-flex justify-center items-center px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Generate QR
                    </button>
                </div>
            </div>

            {{-- Tempat untuk menampilkan QR Code yang sudah di-generate --}}
            <div id="qr-code-container" class="mt-8 flex flex-col items-center justify-center bg-gray-50 p-6 rounded-lg border-2 border-dashed border-gray-300 min-h-[300px]">
                <p class="text-gray-500">QR Code akan ditampilkan di sini setelah digenerate.</p>
                {{-- Contoh Tampilan setelah QR digenerate --}}
                {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=ContohData" alt="QR Code" class="w-64 h-64">
                <p class="mt-4 font-semibold text-gray-700">QR untuk Kelas 7A - Fiqih (08:00 - 09:30)</p> --}}
            </div>
        </div>
    </div>