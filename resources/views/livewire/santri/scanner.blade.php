<div>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>Pindai QR Code Absensi</h4>
                    </div>
                    <div class="card-body text-center">
                        <p>Arahkan kamera ke QR Code yang ditampilkan oleh petugas piket.</p>
                        
                        {{-- Elemen ini akan menjadi tempat viewfinder kamera --}}
                        <div id="qr-reader" style="width:100%;"></div>

                        {{-- Tampilan saat kamera tidak bisa diakses --}}
                        <div id="qr-reader-error" class="alert alert-danger" style="display: none;">
                            Gagal mengakses kamera. Pastikan Anda telah memberikan izin (allow) untuk penggunaan kamera di browser ini.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 1. Sertakan library html5-qrcode dari CDN --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        // Pastikan script berjalan setelah seluruh halaman dimuat
        document.addEventListener('DOMContentLoaded', function () {
            
            // 2. Fungsi yang akan dijalankan jika scan BERHASIL
            function onScanSuccess(decodedText, decodedResult) {
                // `decodedText` berisi URL dari QR code.
                console.log(`Scan berhasil, hasilnya: ${decodedText}`);

                // Hentikan pemindai setelah berhasil
                html5QrcodeScanner.clear();

                // Arahkan pengguna ke URL yang didapat dari QR code
                window.location.href = decodedText;
            }

            // 3. Fungsi yang akan dijalankan jika terjadi ERROR
            function onScanFailure(error) {
                // Fungsi ini sering dipanggil, jadi kita bisa biarkan kosong atau
                // hanya log ke konsol untuk debugging.
                // console.warn(`Scan gagal, error = ${error}`);
            }

            // 4. Buat instance baru dari pemindai
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", // ID dari div tempat kamera akan ditampilkan
                { 
                    fps: 10, // Frames per second, 10 sudah cukup
                    qrbox: { width: 250, height: 250 } // Ukuran kotak pemindai di tengah layar
                },
                /* verbose= */ false
            );

            // 5. Jalankan pemindai
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        });
    </script>
</div>
