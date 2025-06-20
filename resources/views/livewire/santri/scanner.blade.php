<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background-color: #1a1a1a; 
        color: #f0f0f0;
    }

    .scanner-wrapper {
        position: relative;
        width: 100%;
        height: 100vh; 
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        box-sizing: border-box;
    }

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

    #qr-reader {
        width: 100%;
        max-width: 500px;
        border: none;
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        background-color: #333;
    }
    
    #qr-reader::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, rgba(0,255,150,0), rgba(0,255,150,0.8), rgba(0,255,150,0));
        box-shadow: 0 0 10px rgba(0, 255, 150, 0.7);
        animation: scanline 2.5s linear infinite;
    }

    @keyframes scanline {
        0% { transform: translateY(0); }
        100% { transform: translateY(calc(100% - 4px)); }
    }
    
    #scan-success-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(26, 178, 106, 0.9);
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        color: white;
    }
    .success-icon i {
        font-size: 60px;
        margin-bottom: 20px;
    }
    .success-message {
        font-size: 1.5em;
        font-weight: 500;
    }

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
    <div class="scanner-wrapper">
        
        <div class="scanner-header">
            <h4>Pindai QR Code Absensi</h4>
            <p>Arahkan kamera ke QR Code yang disediakan.</p>
        </div>

        <div id="qr-reader"></div>
        
        <div id="qr-reader-error" class="alert alert-danger" style="display: none;">
            <i class="fas fa-exclamation-triangle"></i> Gagal mengakses kamera. Pastikan Anda telah memberikan izin (allow) untuk penggunaan kamera.
        </div>
    </div>

    <div id="scan-success-overlay">
        <div class="success-icon"><i class="fas fa-check-circle"></i></div>
        <div class="success-message">Scan Berhasil!</div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            const successOverlay = document.getElementById('scan-success-overlay');

            let html5QrcodeScanner;

            function onScanSuccess(decodedText, decodedResult) {
                console.log(`Scan berhasil, hasilnya: ${decodedText}`);

                if (html5QrcodeScanner && html5QrcodeScanner.getState() === Html5QrcodeScannerState.SCANNING) {
                    html5QrcodeScanner.clear().catch(error => {
                        console.error("Gagal menghentikan scanner.", error);
                    });
                }
                
                successOverlay.style.display = 'flex';

                setTimeout(() => {
                    window.location.href = decodedText;
                }, 1500);
            }

            function onScanFailure(error) {
            }

            html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader",
                { 
                    fps: 10,
                    qrbox: (viewfinderWidth, viewfinderHeight) => {
                        let minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                        let qrboxSize = Math.floor(minEdge * 0.8);
                        return {
                            width: qrboxSize,
                            height: qrboxSize
                        };
                    },
                    facingMode: "environment" 
                },
                /* verbose= */ false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
</div>