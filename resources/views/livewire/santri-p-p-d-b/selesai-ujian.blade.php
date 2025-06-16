@push('styles')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
    }
    .card-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    .success-animation {
        animation: successPulse 2s ease-in-out infinite;
    }
    @keyframes successPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
</style>
@endpush

<div class="gradient-bg min-h-screen flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Success Card -->
        <div class="bg-white rounded-xl card-shadow p-8 text-center">
            <!-- Success Icon -->
            <div class="bg-green-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 success-animation">
                <svg class="w-12 h-12 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Ujian Berhasil Diselesaikan!</h1>
            <p class="text-lg text-gray-600 mb-8">Terima kasih telah mengerjakan ujian dengan baik</p>

            <!-- Exam Summary -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <div class="w-2 h-6 bg-green-600 rounded-full"></div>
                    Ringkasan Ujian
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Mata Pelajaran</span>
                            <span class="text-gray-900">{{ $ujian->mata_pelajaran }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Jenis Ujian</span>
                            <span class="text-gray-900">{{ $ujian->nama_ujian }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Tanggal</span>
                            <span class="text-gray-900">{{ $ujian->tanggal_ujian->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Waktu Mulai</span>
                            <span class="text-gray-900">{{ $waktuMulai->format('H:i') }}</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Waktu Selesai</span>
                            <span class="text-gray-900">{{ $waktuSelesai->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Durasi Pengerjaan</span>
                            <span class="text-gray-900">{{ $durasiPengerjaan }} menit</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Soal Terjawab</span>
                            <span class="text-gray-900">{{ $soalTerjawab }} dari {{ $jumlahSoal }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Status</span>
                            <span class="text-green-600 font-semibold">{{ $hasilUjian->status === 'selesai' ? 'Berhasil Dikirim' : 'Sedang Diproses' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid md:grid-cols-3 gap-4 mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $soalTerjawab }}</div>
                    <div class="text-sm text-blue-700">Soal Terjawab</div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600">{{ $durasiPengerjaan }}</div>
                    <div class="text-sm text-green-700">Menit Pengerjaan</div>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-purple-600">{{ round(($soalTerjawab / $jumlahSoal) * 100) }}%</div>
                    <div class="text-sm text-purple-700">Tingkat Penyelesaian</div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 text-left">
                <h3 class="text-lg font-bold text-blue-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Informasi Selanjutnya
                </h3>
                <ul class="space-y-2 text-blue-700">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Hasil ujian akan diumumkan dalam 2-3 hari kerja</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Anda akan mendapat notifikasi melalui email dan dashboard</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Pembahasan soal akan tersedia setelah pengumuman hasil</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Jika ada pertanyaan, silakan hubungi guru mata pelajaran</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('santri.dashboard-ujian') }}" class="px-8 py-3 bg-primary text-white rounded-lg font-medium hover:bg-primary-dark transition duration-300 shadow-lg">
                    Kembali ke Dashboard
                </a>
             
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add confetti effect (simple version)
        function createConfetti() {
            const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.left = Math.random() * 100 + 'vw';
                    confetti.style.top = '-10px';
                    confetti.style.width = '10px';
                    confetti.style.height = '10px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.borderRadius = '50%';
                    confetti.style.pointerEvents = 'none';
                    confetti.style.zIndex = '9999';
                    confetti.style.animation = 'fall 3s linear forwards';
                    
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 3000);
                }, i * 100);
            }
        }

        // Add CSS for falling animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fall {
                to {
                    transform: translateY(100vh) rotate(360deg);
                }
            }
        `;
        document.head.appendChild(style);

        // Trigger confetti on page load
        setTimeout(createConfetti, 500);
    });
</script>
@endpush 