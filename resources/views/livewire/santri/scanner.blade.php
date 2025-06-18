<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
    /* Komentar: Menghilangkan margin dan padding default dari body. */
    body {
        margin: 0;
        padding: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background-color: #1a1a1a; /* Latar belakang gelap untuk fokus ke scanner */
        color: #f0f0f0;
    }

    /* Komentar: Container utama yang membungkus seluruh elemen scanner. */
    .scanner-wrapper {
        position: relative;
        width: 100%;
        /* Menggunakan viewport height agar memenuhi layar vertikal */
        height: 100vh; 
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        box-sizing: border-box; /* Agar padding tidak menambah ukuran elemen */
    }

    /* Komentar: Styling untuk judul di bagian atas. */
    .scanner-header {
        text-align: center;
        margin-bottom: 15px;
    }
    .scanner-header h4 {
        font-weight: 600;
        margin-bottom: 5px;
    }
    .scanner-header p {
        color: #b0b0b0;
        font-size: 0.9em;
    }

    /* Komentar: Area di mana viewfinder kamera akan muncul. */
    #qr-reader {
        width: 100%;
        max-width: 500px; /* Batas lebar maksimum di tablet/desktop */
        border: none;
        position: relative;
        overflow: hidden; /* Sembunyikan apa pun yang keluar dari border */
        border-radius: 12px; /* Sudut yang lebih tumpul */
        background-color: #333; /* Warna latar saat kamera belum aktif */
    }
    
    /* Komentar: Pseudo-element untuk membuat animasi garis pemindai (scanline). */
    #qr-reader::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px; /* Ketebalan garis */
        background: linear-gradient(90deg, rgba(0,255,150,0), rgba(0,255,150,0.8), rgba(0,255,150,0));
        box-shadow: 0 0 10px rgba(0, 255, 150, 0.7);
        animation: scanline 2.5s linear infinite; /* Panggil animasi */
    }

    /* Komentar: Definisi animasi 'scanline' dari atas ke bawah. */
    @keyframes scanline {
        0% { transform: translateY(0); }
        100% { transform: translateY(calc(100% - 4px)); }
    }
    
    /* Komentar: Tampilan untuk feedback saat scan berhasil. */
    #scan-success-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(26, 178, 106, 0.9); /* Latar hijau semi-transparan */
        display: none; /* Disembunyikan secara default */
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        color: white;
    }
    .success-icon i {
        font-size: 60px; /* Ukuran ikon centang */
        margin-bottom: 20px;
    }
    .success-message {
        font-size: 1.5em;
        font-weight: 500;
    }

    /* Komentar: Styling untuk pesan error, dibuat lebih menonjol. */
    #qr-reader-error {
        margin-top: 15px;
        padding: 15px;
        border-radius: 8px;
        width: 100%;
        max-width: 500px;
        box-sizing: border-box;
    }
</style>

<div>
    {{-- Komentar: Container utama untuk halaman scanner. --}}
    <div class="scanner-wrapper">
        
        {{-- Komentar: Judul dan instruksi ditempatkan di atas scanner. --}}
        <div class="scanner-header">
            <h4>Pindai QR Code Absensi</h4>
            <p>Arahkan kamera ke QR Code yang disediakan.</p>
        </div>

        {{-- Komentar: Elemen ini akan menjadi tempat viewfinder kamera. --}}
        <div id="qr-reader"></div>
        
        {{-- Komentar: Tampilan saat kamera tidak bisa diakses atau ada error. --}}
        <div id="qr-reader-error" class="alert alert-danger" style="display: none;">
            <i class="fas fa-exclamation-triangle"></i> Gagal mengakses kamera. Pastikan Anda telah memberikan izin (allow) untuk penggunaan kamera.
        </div>
    </div>

    {{-- Komentar: Overlay yang akan muncul saat scan berhasil. --}}
    <div id="scan-success-overlay">
        <div class="success-icon"><i class="fas fa-check-circle"></i></div>
        <div class="success-message">Scan Berhasil!</div>
    </div>

    {{-- Komentar: 1. Sertakan library html5-qrcode dari CDN. --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        // Komentar: Pastikan script berjalan setelah seluruh halaman dimuat.
        document.addEventListener('DOMContentLoaded', function () {
            
            // Komentar: Ambil elemen overlay dari DOM untuk dimanipulasi nanti.
            const successOverlay = document.getElementById('scan-success-overlay');

            // Komentar: Variabel untuk menyimpan instance scanner agar bisa diakses di fungsi lain.
            let html5QrcodeScanner;

            // Komentar: 2. Fungsi yang akan dijalankan jika scan BERHASIL.
            function onScanSuccess(decodedText, decodedResult) {
                // `decodedText` berisi URL dari QR code.
                console.log(`Scan berhasil, hasilnya: ${decodedText}`);

                // Komentar: Hentikan pemindai setelah berhasil untuk menghemat resource.
                if (html5QrcodeScanner && html5QrcodeScanner.getState() === Html5QrcodeScannerState.SCANNING) {
                    html5QrcodeScanner.clear().catch(error => {
                        console.error("Gagal menghentikan scanner.", error);
                    });
                }
                
                // Komentar: Tampilkan overlay "berhasil" untuk feedback ke pengguna.
                successOverlay.style.display = 'flex';

                // Komentar: Tunggu 1.5 detik sebelum mengarahkan pengguna ke halaman tujuan.
                // Ini memberi waktu bagi pengguna untuk melihat pesan sukses.
                setTimeout(() => {
                    window.location.href = decodedText;
                }, 1500);
            }

            // Komentar: 3. Fungsi yang akan dijalankan jika terjadi ERROR saat memindai.
            function onScanFailure(error) {
                // Komentar: Fungsi ini sering dipanggil jika tidak ada QR code terdeteksi, 
                // jadi kita biarkan kosong agar tidak mengganggu konsol atau pengguna.
            }

            // Komentar: 4. Buat instance baru dari pemindai.
            html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", // ID dari div tempat kamera akan ditampilkan
                { 
                    fps: 10, // Frames per second, 10 sudah cukup.
                    // Menggunakan persentase agar lebih responsif di berbagai ukuran layar.
                    qrbox: (viewfinderWidth, viewfinderHeight) => {
                        // Menentukan ukuran kotak pemindai, 80% dari sisi terpendek viewfinder.
                        let minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                        let qrboxSize = Math.floor(minEdge * 0.8);
                        return {
                            width: qrboxSize,
                            height: qrboxSize
                        };
                    },
                    // Mengaktifkan kamera belakang secara default di ponsel.
                    facingMode: "environment" 
                },
                /* verbose= */ false
            );

            // Komentar: 5. Jalankan pemindai.
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
</div>