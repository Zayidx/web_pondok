<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Pastikan Carbon diimpor

class CheckStatus extends Component
{
    use WithoutUrlPagination;
    public $santri = null;
    public $timelineStatus = [
        'pendaftaran_online' => [
            'completed' => false,
            'date' => null,
        ],
        'wawancara' => [
            'completed' => false,
            'current' => false,
            'date' => null,
            'time' => null,
            'mode' => null,
            'location' => null,
        ],
        'ujian' => [
            'completed' => false,
            'current' => false,
            'date' => null,
        ],
        'pengumuman_hasil' => [
            'completed' => false,
            'current' => false,
            'status' => null,
            'date' => null,
        ],
        'daftar_ulang' => [
            'completed' => false,
            'current' => false,
            'date' => null,
        ],
    ];

    public function mount()
    {
        $santriId = session('santri_id');
        if (!$santriId) {
            Log::warning('No santri_id in session, redirecting to login');
            return redirect()->route('login-ppdb-santri');
        }

        $this->santri = PendaftaranSantri::where('id', $santriId)->first();
        if (!$this->santri) {
            Log::warning('Santri not found for ID: ' . $santriId);
            session()->forget('santri_id');
            return redirect()->route('login-ppdb-santri');
        }

        $this->updateTimelineStatus();

        Log::info('Santri data retrieved for logged-in user', [
            'santri_id' => $this->santri->id,
            'nama_lengkap' => $this->santri->nama_lengkap,
            'nisn' => $this->santri->nisn,
            'status_santri' => $this->santri->status_santri,
            'timeline_status' => $this->timelineStatus,
        ]);
    }

    protected function updateTimelineStatus()
    {
        if (!$this->santri) {
            return;
        }

        $this->timelineStatus['pendaftaran_online'] = [
            'completed' => true,
            'date' => $this->santri->created_at ? $this->santri->created_at->format('d F Y') : 'N/A',
        ];

        $this->timelineStatus['wawancara'] = [
            'completed' => in_array($this->santri->status_santri, ['sedang_ujian', 'diterima', 'ditolak']),
            'current' => $this->santri->status_santri == 'wawancara',
            'date' => $this->santri->tanggal_wawancara ? Carbon::parse($this->santri->tanggal_wawancara)->format('d F Y') : null,
            'time' => $this->santri->tanggal_wawancara ? Carbon::parse($this->santri->tanggal_wawancara)->format('H:i') : null,
            'mode' => $this->santri->mode ?? 'offline',
            'location' => $this->santri->mode == 'offline' ? ($this->santri->lokasi_offline ?? 'Ruang Wawancara') : ($this->santri->link_online ?? '#'),
        ];

        $this->timelineStatus['ujian'] = [
            'completed' => in_array($this->santri->status_santri, ['diterima', 'ditolak']),
            'current' => $this->santri->status_santri == 'sedang_ujian',
            'date' => $this->santri->updated_at && in_array($this->santri->status_santri, ['diterima', 'ditolak']) 
                ? $this->santri->updated_at->format('d F Y') 
                : null,
        ];

        $this->timelineStatus['pengumuman_hasil'] = [
            'completed' => in_array($this->santri->status_santri, ['diterima', 'ditolak']),
            'current' => false,
            'status' => in_array($this->santri->status_santri, ['diterima', 'ditolak']) ? $this->santri->status_santri : null,
            'date' => $this->santri->updated_at && in_array($this->santri->status_santri, ['diterima', 'ditolak']) 
                ? $this->santri->updated_at->format('d F Y') 
                : null,
        ];

        $this->timelineStatus['daftar_ulang'] = [
            'completed' => $this->santri->status_santri === 'diterima',
            'current' => $this->santri->status_santri === 'daftar_ulang',
            'date' => $this->santri->tanggal_pembayaran ? Carbon::parse($this->santri->tanggal_pembayaran)->format('d F Y') : null
        ];
    }

    public function getTimelineStatusProperty()
    {
        $status = $this->santri->status_santri;
        $now = now();

        return [
            'pendaftaran' => [
                'completed' => in_array($status, ['menunggu', 'wawancara', 'sedang_ujian', 'diterima', 'ditolak', 'daftar_ulang']),
                'current' => $status === 'menunggu',
                'date' => $this->santri->created_at->format('d M Y')
            ],
            'wawancara' => [
                'completed' => in_array($status, ['sedang_ujian', 'diterima', 'ditolak', 'daftar_ulang']),
                'current' => $status === 'wawancara',
                'date' => optional($this->santri->tanggal_wawancara)->format('d M Y')
            ],
            'ujian' => [
                'completed' => in_array($status, ['diterima', 'ditolak', 'daftar_ulang']),
                'current' => $status === 'sedang_ujian',
                'date' => optional($this->santri->tanggal_ujian)->format('d M Y')
            ],
            'daftar_ulang' => [
                'completed' => $status === 'diterima',
                'current' => $status === 'daftar_ulang',
                'date' => optional($this->santri->tanggal_pembayaran)->format('d M Y')
            ]
        ];
    }

    public function testClick()
    {
        Log::info('Test click triggered');
    }

    public function logout()
    {
        try {
            Log::info('Attempting to logout santri', ['santri_id' => session('santri_id')]);

            session()->forget(['santri_id', 'login_time']);

            if (Auth::guard('pendaftaran_santri')->check()) {
                Auth::guard('pendaftaran_santri')->logout();
            }

            Log::info('Logout successful, redirecting to login page');

            $this->dispatch('redirect', url: route('login-ppdb-santri'));
        } catch (\Exception $e) {
            Log::error('Error during logout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('redirect', url: route('login-ppdb-santri'));
        }
    }

    public function downloadCertificate()
    {
        // --- DEBUGGING: Log ini akan muncul di log Laravel (storage/logs/laravel.log) ---
        Log::info('downloadCertificate method triggered in Livewire component.');
        // --- END DEBUGGING ---

        // Hanya izinkan unduhan jika status santri adalah 'diterima'
        if (!$this->santri || $this->santri->status_santri !== 'diterima') {
            Log::warning('Attempted to download certificate for santri not in "diterima" status or santri not found.', [
                'santri_id' => $this->santri->id ?? 'N/A',
                'current_status' => $this->santri->status_santri ?? 'N/A'
            ]);
            return;
        }

        $santriName = $this->santri->nama_lengkap;
        // Gunakan tanggal `updated_at` sebagai tanggal penerimaan dan tanggal terbit
        $acceptanceDate = $this->santri->updated_at ? $this->santri->updated_at->format('d F Y') : Carbon::now()->format('d F Y');
        $issueDate = $this->santri->updated_at ? $this->santri->updated_at->format('d F Y') : Carbon::now()->format('d F Y');
        // Buat nomor surat acak dengan format CERT/TAHUN/ACAK_6_DIGIT
        $certificateNumber = 'CERT/' . date('Y') . '/' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

        // Konten HTML sertifikat yang dinamis
        $htmlContent = '
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Sertifikat Penerimaan - Santri Baru</title>
                <script src="https://cdn.tailwindcss.com"></script>
                <style>
                    /* CSS yang sama dengan sebelumnya */
                    @page {
                        size: A4;
                        margin: 0;
                    }

                    body {
                        font-family: "Inter", sans-serif; /* Menggunakan font Inter */
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                        background-color: #f8fafc; /* Warna latar belakang sesuai Tailwind gray-50 */
                    }

                    .a4-page {
                        width: 210mm;
                        height: 297mm;
                        margin: 0 auto;
                        background: white;
                        box-shadow: 0 0 20px rgba(0,0,0,0.1);
                        position: relative;
                        overflow: hidden;
                    }

                    .ornament-corner {
                        position: absolute;
                        width: 80px;
                        height: 80px;
                        background: linear-gradient(45deg, #3b82f6, #1d4ed8);
                        clip-path: polygon(0 0, 100% 0, 0 100%);
                    }

                    .ornament-corner.top-left {
                        top: 0;
                        left: 0;
                    }

                    .ornament-corner.top-right {
                        top: 0;
                        right: 0;
                        transform: rotate(90deg);
                    }

                    .ornament-corner.bottom-left {
                        bottom: 0;
                        left: 0;
                        transform: rotate(-90deg);
                    }

                    .ornament-corner.bottom-right {
                        bottom: 0;
                        right: 0;
                        transform: rotate(180deg);
                    }

                    .border-ornament {
                        border: 4px solid #3b82f6;
                        border-image: linear-gradient(45deg, #3b82f6, #1d4ed8, #3b82f6) 1;
                    }

                    .certificate-bg {
                        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                    }

                    .seal {
                        width: 120px;
                        height: 120px;
                        border-radius: 50%;
                        background: linear-gradient(45deg, #3b82f6, #1d4ed8);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        position: relative;
                        box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
                    }

                    .seal::before {
                        content: \'\';
                        position: absolute;
                        width: 100px;
                        height: 100px;
                        border-radius: 50%;
                        border: 2px solid white;
                    }

                    .signature-line {
                        border-bottom: 2px solid #374151;
                        width: 200px;
                        margin: 0 auto;
                    }

                    .watermark {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%) rotate(-45deg);
                        font-size: 120px;
                        color: rgba(59, 130, 246, 0.05);
                        font-weight: bold;
                        z-index: 1;
                        pointer-events: none;
                    }
                </style>
                <!-- Link ke font Inter dari Google Fonts -->
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
            </head>
            <body class="bg-gray-100">
                <!-- A4 Certificate Page -->
                <div class="a4-page certificate-bg">
                    <!-- Watermark -->
                    <div class="watermark">DITERIMA</div>

                    <!-- Corner Ornaments -->
                    <div class="ornament-corner top-left"></div>
                    <div class="ornament-corner top-right"></div>
                    <div class="ornament-corner bottom-left"></div>
                    <div class="ornament-corner bottom-right"></div>

                    <!-- Main Content -->
                    <div class="h-full flex flex-col justify-between p-12 relative z-10">
                        <!-- Header -->
                        <div class="text-center">
                            <!-- Logo and School Info -->
                            <div class="flex items-center justify-center mb-6">
                                <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mr-6">
                                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <h1 class="text-2xl font-bold text-gray-800">PESANTREN AL-HIKMAH</h1>
                                    <p class="text-lg text-gray-600">Yayasan Pendidikan Islam Terpadu</p>
                                    <p class="text-sm text-gray-500">Jl. Pendidikan No. 123, Jakarta Selatan 12345</p>
                                    <p class="text-sm text-gray-500">Telp: (021) 1234-5678 | Email: info@alhikmah.ac.id</p>
                                </div>
                            </div>

                            <!-- Certificate Title -->
                            <div class="border-ornament rounded-lg p-6 mb-8 bg-white shadow-lg">
                                <h2 class="text-4xl font-bold text-blue-600 mb-2">SURAT PENERIMAAN SANTRI</h2>
                                <p class="text-xl text-gray-600">SANTRI BARU TAHUN AJARAN 2024/2025</p>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="flex-1 flex flex-col justify-center">
                            <!-- Congratulations Message -->
                            <div class="text-center mb-8">
                                <p class="text-lg text-gray-700 mb-4">Dengan ini menyatakan bahwa:</p>

                                <!-- Student Name -->
                                <div class="bg-white rounded-lg p-6 shadow-lg border-2 border-blue-200 mb-6">
                                    <h3 class="text-3xl font-bold text-blue-800 mb-2">' . $santriName . '</h3>
                                    <p class="text-lg text-gray-600">Telah diterima sebagai Santri Baru</p>
                                </div>

                                <p class="text-lg text-gray-700">
                                    Telah <span class="font-bold text-green-600">DITERIMA</span> dan terdaftar sebagai santri baru di
                                    <span class="font-bold text-blue-600">Pesantren Al-Hikmah</span> untuk mengikuti pendidikan
                                    pada Tahun Ajaran <span class="font-bold">2024/2025</span>
                                </p>
                            </div>
                        </div>

                        <!-- Footer with Signatures -->
                        <div>
                            <!-- Important Notes -->
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                <h5 class="font-bold text-yellow-800 mb-2">CATATAN PENTING:</h5>
                                <ul class="text-sm text-yellow-700 space-y-1">
                                    <li>• Orientasi santri baru dimulai tanggal 15 Juli 2024</li>
                                    <li>• Harap membawa Surat ini saat registrasi ulang</li>
                                    <li>• Pembayaran SPP semester pertama paling lambat 20 Juli 2024</li>
                                    <li>• Untuk informasi lebih lanjut hubungi bagian administrasi</li>
                                </ul>
                            </div>

                            <!-- Signatures -->
                            <div class="flex justify-between items-end">
                                <!-- Date and Place -->
                                <div class="text-left">
                                    <p class="text-gray-700 mb-8">Jakarta, ' . $acceptanceDate . '</p>
                                    <div class="text-center">
                                        <div class="seal mx-auto mb-4">
                                            <div class="text-white text-center">
                                                <div class="text-xs font-bold">PESANTREN</div>
                                                <div class="text-xs font-bold">AL-HIKMAH</div>
                                                <div class="text-xs">2024</div>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600">Stempel Resmi</p>
                                    </div>
                                </div>

                                <!-- Director Signature -->
                                <div class="text-center">
                                    <p class="text-gray-700 mb-2">Direktur Pesantren</p>
                                    <div class="signature-line mb-4"></div>
                                    <p class="font-bold text-gray-800">Dr. H. Abdul Rahman, M.Pd</p>
                                    <p class="text-sm text-gray-600">NIK: 1234567890123456</p>
                                </div>

                                <!-- Admin Signature -->
                                <div class="text-center">
                                    <p class="text-gray-700 mb-2">Kepala Administrasi</p>
                                    <div class="signature-line mb-4"></div>
                                    <p class="font-bold text-gray-800">Hj. Fatimah, S.Pd</p>
                                    <p class="text-sm text-gray-600">NIK: 9876543210987654</p>
                                </div>
                            </div>

                            <!-- Certificate Number -->
                            <div class="text-center mt-6 pt-4 border-t border-gray-300">
                                <p class="text-sm text-gray-500">
                                    No. Surat: <span class="font-mono font-bold">' . $certificateNumber . '</span> |
                                    Tanggal Terbit: ' . $issueDate . '
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ';

        // Nama file PDF yang akan diunduh
        $fileName = 'Sertifikat_Penerimaan_' . str_replace(' ', '_', $santriName) . '.pdf';

        // Mengirim event ke browser dengan konten HTML dan nama file
        // Browser akan menangkap event ini dan melakukan POST ke rute yang menangani konversi PDF
        $this->dispatch('downloadPdf', html: $htmlContent, fileName: $fileName);

        Log::info('Dispatched downloadPdf event', [
            'santri_id' => $this->santri->id,
            'file_name' => $fileName,
            'certificate_number' => $certificateNumber,
        ]);
    }

    public function render()
    {
        return view('livewire.guest.check-status', [
            'title' => 'Cek Status Pendaftaran'
        ])
        ->extends('components.layouts.check-status')
        ->response(function ($response) {
            $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        });
    }
}
