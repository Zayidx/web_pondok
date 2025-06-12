<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

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
            if (Auth::guard('pendaftaran_santri')->check()) {
                $this->santri = Auth::guard('pendaftaran_santri')->user();
                session(['santri_id' => $this->santri->id]);
            } else {
                Log::warning('No santri_id in session and not authenticated, redirecting to login');
                return redirect()->route('login-ppdb-santri');
            }
        } else {
            $this->santri = PendaftaranSantri::where('id', $santriId)->first();
        }

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

        $this->santri->load(['wali', 'periode']);

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

    public function triggerDownloadPdf()
    {
        Log::info('triggerDownloadPdf method called from Livewire component.');

        if (!$this->santri || $this->santri->status_santri !== 'diterima') {
            Log::warning('Trigger PDF download denied: Santri not found or not accepted.');
            session()->flash('error', 'Surat penerimaan tidak dapat diunduh. Pastikan status pendaftaran sudah DITERIMA.');
            return;
        }

        // URL untuk mengunduh PDF langsung dari Laravel Route
        // Gunakan $this->santri->id yang sudah tersedia
        $downloadUrl = route('psb.download-penerimaan-pdf', ['santriId' => $this->santri->id]);
        
        Log::info('Dispatching openPdfInNewTab event with URL: ' . $downloadUrl);
        // Dispatch event ke JavaScript untuk membuka URL ini di tab baru
        $this->dispatch('openPdfInNewTab', ['url' => $downloadUrl]);

        session()->flash('message', 'Unduhan PDF dimulai di tab baru.');
    }

    // Metode untuk menghasilkan PDF yang akan dipanggil oleh rute langsung
    public function downloadCertificatePdfDirect()
    {
        Log::info('downloadCertificatePdfDirect method called directly from route.');

        // Pada titik ini, $this->santri sudah diinisialisasi oleh mount()
        // karena rute secara manual memanggil mount() sebelum memanggil metode ini.
        if (!$this->santri || $this->santri->status_santri !== 'diterima') {
            Log::warning('Direct PDF download denied: Santri not found or not accepted for santri ID (from component): ' . ($this->santri->id ?? 'N/A'));
            // Jika santri tidak diterima, arahkan ke login atau tampilkan error
            abort(403, 'Akses tidak diizinkan atau santri tidak ditemukan/diterima.');
        }

        $santriName = $this->santri->nama_lengkap;
        $acceptanceDate = $this->santri->updated_at ? $this->santri->updated_at->translatedFormat('d F Y') : Carbon::now()->translatedFormat('d F Y');
        $issueDate = Carbon::now()->translatedFormat('d F Y');
        $certificateNumber = 'CERT/' . date('Y') . '/' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

        $logoPath = public_path('assets/compiled/jpg/1.jpg');
        $logoBase64 = '';

        if (File::exists($logoPath)) {
            $logoBase64 = 'data:image/' . File::extension($logoPath) . ';base64,' . base64_encode(File::get($logoPath));
        } else {
            Log::error("Logo file not found at: " . $logoPath);
        }

        $data = [
            'santri' => $this->santri,
            'tanggal_cetak' => Carbon::now()->translatedFormat('d F Y'),
            'jenjang_diterima' => $this->santri->nama_jenjang ?? 'SMA',
            'periode_pendaftaran' => $this->santri->periode->nama_periode ?? 'Tahun Ajaran 2025/2026',
            'acceptanceDate' => $acceptanceDate,
            'issueDate' => $issueDate,
            'certificateNumber' => $certificateNumber,
            'logoBase64' => $logoBase64,
        ];

        try {
            $pdf = Pdf::loadView('psb.surat-penerimaan-pdf', $data);
            $fileName = 'Surat_Penerimaan_' . str_replace(' ', '_', $santriName) . '.pdf';
            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error("Error generating PDF directly: " . $e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile());
            abort(500, 'Terjadi kesalahan internal saat membuat PDF.');
        }
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