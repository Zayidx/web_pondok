<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Penerimaan - {{ $data->nama_lengkap }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
        }

        .a4-page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
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

        .ornament-corner.top-left { top: 0; left: 0; }
        .ornament-corner.top-right { top: 0; right: 0; transform: rotate(90deg); }
        .ornament-corner.bottom-left { bottom: 0; left: 0; transform: rotate(-90deg); }
        .ornament-corner.bottom-right { bottom: 0; right: 0; transform: rotate(180deg); }

        .border-ornament {
            border: 4px solid #3b82f6;
            border-image: linear-gradient(45deg, #3b82f6, #1d4ed8, #3b82f6) 1;
        }

        .certificate-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
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

        .signature-line {
            border-bottom: 2px solid #374151;
            width: 200px;
            margin: 0 auto;
        }

        .content {
            padding: 40px;
            position: relative;
            z-index: 2;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 18px;
            color: #4b5563;
        }

        .student-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px 0;
        }

        .student-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 40px;
        }

        .notes {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-bottom: 30px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature-box {
            text-align: center;
            flex: 1;
        }

        .certificate-number {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }

        .catatan-penting {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="a4-page certificate-bg">
        <!-- Watermark -->
        <div class="watermark">DITERIMA</div>
        
        <!-- Corner Ornaments -->
        <div class="ornament-corner top-left"></div>
        <div class="ornament-corner top-right"></div>
        <div class="ornament-corner bottom-left"></div>
        <div class="ornament-corner bottom-right"></div>

        <div class="content">
            <!-- Header -->
            <div class="header">
                @if($settings->logo_base64)
                    <img src="{{ $settings->logo_base64 }}" alt="Logo Pesantren" class="logo">
                @endif
                <div class="title">{{ $settings->nama_pesantren }}</div>
                <div class="subtitle">{{ $settings->nama_yayasan }}</div>
                <div>{{ $settings->alamat_pesantren }}</div>
                <div>{{ $settings->telepon_pesantren }} | {{ $settings->email_pesantren }}</div>
            </div>

            <!-- Title -->
            <div class="border-ornament" style="text-align: center; padding: 20px; margin-bottom: 30px;">
                <h2 style="font-size: 24px; font-weight: bold; color: #1e40af; margin: 0;">SERTIFIKAT PENERIMAAN</h2>
                <p style="font-size: 18px; color: #4b5563; margin: 5px 0 0;">SANTRI BARU PERIODE PENDAFTARAN</p>
            </div>

            <!-- Content -->
            <div style="text-align: center; margin-bottom: 30px;">
                <p style="font-size: 16px; margin-bottom: 20px;">Dengan ini menyatakan bahwa:</p>
                
                <div class="student-info">
                    <div class="student-name">{{ $data->nama_lengkap }}</div>
                    <p>NISN: {{ $data->nisn }}</p>
                    <p style="font-size: 16px; color: #4b5563;">Telah diterima sebagai Santri Baru</p>
                </div>

                <p style="font-size: 16px; margin: 20px 0;">
                    Telah <span style="font-weight: bold; color: #059669;">DITERIMA</span> dan terdaftar sebagai santri baru di 
                    <span style="font-weight: bold; color: #1e40af;">{{ $settings->nama_pesantren }}</span> untuk mengikuti pendidikan 
                    pada Tahun {{ date('Y') }}
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <!-- Notes -->
                @if(!empty($settings->catatan_penting))
                    <div class="catatan-penting">
                        <p><strong>Catatan Penting:</strong></p>
                        <p style="white-space: pre-line;">{{ $settings->catatan_penting }}</p>
                    </div>
                @endif

                <!-- Signatures -->
                <div class="signatures">
                    <div class="signature-box">
                        <p>{{ date('d F Y') }}</p>
                        @if($settings->stempel_base64)
                            <img src="{{ $settings->stempel_base64 }}" alt="Stempel" style="max-width: 100px; margin: 10px 0;">
                        @endif
                    </div>

                    <div class="signature-box">
                        <p>Direktur Pesantren</p>
                        <div class="signature-line"></div>
                        <p style="font-weight: bold; margin-top: 5px;">{{ $settings->nama_direktur }}</p>
                        <p style="font-size: 12px; color: #6b7280;">NIP: {{ $settings->nip_direktur }}</p>
                    </div>

                    <div class="signature-box">
                        <p>Kepala Administrasi</p>
                        <div class="signature-line"></div>
                        <p style="font-weight: bold; margin-top: 5px;">{{ $settings->nama_kepala_admin }}</p>
                        <p style="font-size: 12px; color: #6b7280;">NIP: {{ $settings->nip_kepala_admin }}</p>
                    </div>
                </div>

                <!-- Certificate Number -->
                <div class="certificate-number">
                    No. Sertifikat: {{ date('Ymd') }}/{{ str_pad($data->id, 4, '0', STR_PAD_LEFT) }} | 
                    Tanggal Terbit: {{ date('d F Y') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html> 