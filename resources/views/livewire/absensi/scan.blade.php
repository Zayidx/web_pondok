{{-- Div utama untuk menengahkan konten di layar --}}
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    {{-- Kotak putih sebagai container konten --}}
    <div class="p-8 bg-white rounded-lg shadow-lg text-center max-w-sm mx-auto">
        {{-- Kondisi: jika status dari komponen Scan.php adalah 'success' --}}
        @if($status === 'success')
            {{-- Tampilkan ikon centang hijau --}}
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mt-4">Berhasil!</h2>
        {{-- Kondisi: jika status bukan 'success' (berarti 'error') --}}
        @else
            {{-- Tampilkan ikon silang merah --}}
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
                <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mt-4">Gagal!</h2>
        @endif
        
        {{-- Tampilkan pesan (sukses atau error) dari komponen Scan.php --}}
        <p class="text-gray-600 mt-2">{{ $message }}</p>

        {{-- [PERBAIKAN] Mengubah nama rute menjadi 'santri.dashboard' --}}
        <a href="{{ route('santri.dashboard') }}" class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
            Kembali ke Dashboard
        </a>
    </div>
</div>