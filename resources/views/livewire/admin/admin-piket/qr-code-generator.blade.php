<div>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.js"></script>

    <div x-data="{ 
            qrUrl: @entangle('qrCodeUrl').live, 
            expires: @entangle('sessionExpiresAt').live, 
            countdown: '',

            updateCountdown() {
                if (!this.expires) { this.countdown = ''; return; }
                const now = new Date();
                const expiryDate = new Date(this.expires);
                const diff = expiryDate.getTime() - now.getTime();
                if (diff <= 0) {
                    this.countdown = 'Kedaluwarsa';
                    if (this.qrUrl) { this.$wire.set('qrCodeUrl', null); }
                    return;
                }
                const minutes = Math.floor(diff / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                this.countdown = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
         }"
         x-init="
            setInterval(() => updateCountdown(), 1000);
            $watch('qrUrl', value => {
                $nextTick(() => {
                    const qrCodeContainer = document.getElementById('qrcode');
                    if (value && qrCodeContainer) {
                        qrCodeContainer.innerHTML = '';
                        let qr = qrcode(0, 'M');
                        qr.addData(value);
                        qr.make();
                        qrCodeContainer.innerHTML = qr.createImgTag(8, 8);
                    } else if (!value && qrCodeContainer) {
                        qrCodeContainer.innerHTML = '';
                    }
                });
            });
         ">

        <h2 class="card-title text-center fw-bold mb-4">QR Code Absensi</h2>
        <div id="qr-code-display" class="d-flex justify-content-center align-items-center flex-column flex-grow-1" style="min-height: 200px;">
            <template x-if="qrUrl">
                <div class="p-3 border rounded">
                    <div id="qrcode"></div>
                    <p class="text-center text-danger fw-bold mt-2" x-text="`Kedaluwarsa dalam: ${countdown}`"></p>
                </div>
            </template>
            <template x-if="!qrUrl">
                <button wire:click="generateNewQrCode" class="btn btn-primary btn-lg">
                    <i class="bi bi-qr-code me-2"></i> Buat QR Code
                </button>
            </template>
        </div>
    </div>
</div>
