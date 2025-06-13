<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Penerimaan - {{ $santri->nama_lengkap ?? 'Preview' }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
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
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .certificate-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }

        .school-info {
            text-align: left;
        }

        .title {
            font-size: 32px;
            color: #1d4ed8;
            margin: 0;
            font-weight: bold;
        }

        .subtitle {
            font-size: 20px;
            color: #4b5563;
            margin: 5px 0;
        }

        .content {
            text-align: center;
            margin: 40px 0;
        }

        .student-name {
            font-size: 28px;
            color: #1d4ed8;
            margin: 20px 0;
            font-weight: bold;
        }

        .student-info {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .important-notes {
            background: #fff9c2;
            border-left: 4px solid #eab308;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature {
            text-align: center;
            flex: 1;
        }

        .stamp {
            width: 120px;
            height: auto;
            margin: 20px 0;
        }

        .signature-line {
            width: 200px;
            border-bottom: 2px solid #374151;
            margin: 10px auto;
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
        }

        .certificate-number {
            text-align: center;
            margin-top: 40px;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    @if(!$settings)
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh; text-align: center;">
        <div>
            <h1 style="color: #ef4444; margin-bottom: 1rem;">Pengaturan Belum Dikonfigurasi</h1>
            <p style="color: #4b5563;">Maaf, Surat Penerimaan Santri belum dikonfigurasi. Silahkan hubungi administrator untuk melakukan pengaturan.</p>
        </div>
    </div>
    @else
    <div class="a4-page certificate-bg">
            <!-- Watermark -->
            <div class="watermark">DITERIMA</div>
        
        <!-- Corner Ornaments -->
        <div class="ornament-corner top-left"></div>
        <div class="ornament-corner top-right"></div>
        <div class="ornament-corner bottom-left"></div>
        <div class="ornament-corner bottom-right"></div>

            <!-- Header -->
            <div class="header">
                <div class="logo-container">
                @if($settings->logo_path && Storage::exists($settings->logo_path))
                    <img src="{{ public_path(Storage::url($settings->logo_path)) }}" alt="Logo" class="logo">
                    @endif
                    <div class="school-info">
                    <h1>{{ $settings->nama_pesantren }}</h1>
                    <p>{{ $settings->nama_yayasan }}</p>
                    <p>{{ $settings->alamat_pesantren }}</p>
                    <p>{{ $settings->nomor_telepon }} | {{ $settings->email_pesantren }}</p>
                    </div>
                </div>

            <div class="border-ornament">
                <h2 class="title">SERTIFIKAT PENERIMAAN</h2>
                <p class="subtitle">SANTRI BARU {{ $periode_pendaftaran }}</p>
                </div>
            </div>

            <!-- Main Content -->
        <div class="content">
            <p>Dengan ini menyatakan bahwa:</p>
            
            <div class="student-info">
                <h3 class="student-name">{{ $santri->nama_lengkap }}</h3>
                <p>NISN: {{ $santri->nisn }}</p>
                    <p>Telah diterima sebagai Santri Baru</p>
                </div>

                <p>
                    Telah <strong style="color: #059669;">DITERIMA</strong> dan terdaftar sebagai santri baru di 
                <strong style="color: #1d4ed8;">{{ $settings->nama_pesantren }}</strong> untuk mengikuti pendidikan 
                pada {{ $periode_pendaftaran }}
                </p>

            <!-- Important Notes -->
            <div class="important-notes">
                <h5 style="margin: 0 0 10px 0;">CATATAN PENTING:</h5>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($settings->catatan_penting ?? [] as $catatan)
                        <li>{{ $catatan }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature">
                    <p>{{ $acceptanceDate }}</p>
                    @if($settings->stempel_path && Storage::exists($settings->stempel_path))
                        <img src="{{ public_path(Storage::url($settings->stempel_path)) }}" alt="Stempel" class="stamp">
                    @endif
                </div>

                <div class="signature">
                    <p>Direktur Pesantren</p>
                    <div class="signature-line"></div>
                    <p><strong>{{ $settings->nama_direktur }}</strong></p>
                    <p>NIP: {{ $settings->nip_direktur }}</p>
                </div>

                <div class="signature">
                    <p>Kepala Administrasi</p>
                    <div class="signature-line"></div>
                    <p><strong>{{ $settings->nama_kepala_admin }}</strong></p>
                    <p>NIP: {{ $settings->nip_kepala_admin }}</p>
                </div>
            </div>

            <!-- Certificate Number -->
            <div class="certificate-number">
                No. Sertifikat: {{ $certificateNumber }} | 
                    Tanggal Terbit: {{ $issueDate }}
            </div>
        </div>
    </div>
    @endif
</body>
</html> 