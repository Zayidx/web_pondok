<div>
    {{-- Div ini memanggil method 'checkScanStatus' setiap 2 detik untuk refresh data --}}
    <div wire:poll.2s="checkScanStatus">
        <div class="text-center">
            <h3>Piket & Absensi QR Code</h3>
            <p>Silakan Scan QR Code di Bawah Ini</p>
        </div>

        {{-- Bagian untuk menampilkan QR Code --}}
        <div class="text-center p-4 border rounded bg-white shadow-sm mx-auto" style="max-width: 350px;">
            @if ($qrCodeUrl)
                {!! QrCode::size(300)->generate($qrCodeUrl) !!}
            @else
                <p>Membuat QR Code...</p>
            @endif
        </div>

        {{-- Tombol untuk membuat QR code baru --}}
        <div class="text-center mt-3">
            <button wire:click="generateNewQrCode" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                    <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                    <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.5A5.002 5.002 0 0 0 8 3zM3.5 13A5.002 5.002 0 0 0 8 15c1.552 0 2.94-.707 3.857-1.818a.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.5A5.002 5.002 0 0 0 8 13z"/>
                </svg>
                Buat QR Code Baru
            </button>
        </div>
    </div>

    {{-- Bagian untuk menampilkan log/daftar yang sudah scan --}}
    <div class="mt-5">
        <h4>Log Absensi</h4>
        {{-- Di sini perubahannya: Cek apakah koleksi $scanLogs tidak kosong --}}
        @if ($scanLogs && $scanLogs->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Santri</th>
                            <th>NISN</th>
                            <th>Waktu Scan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($scanLogs as $log)
                            {{-- Pastikan relasi santri tidak null untuk menghindari error --}}
                            @if ($log->santri)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $log->santri->nama }}</td>
                                <td>{{ $log->santri->nisn }}</td>
                                {{-- Format waktu agar lebih mudah dibaca --}}
                                <td>{{ \Carbon\Carbon::parse($log->scanned_at)->format('H:i:s d-M-Y') }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                Belum ada santri yang melakukan scan untuk sesi ini.
            </div>
        @endif
    </div>
</div>
