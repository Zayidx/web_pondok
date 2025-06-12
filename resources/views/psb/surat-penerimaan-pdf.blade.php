<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Penerimaan - Santri Baru</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .a4-page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
            position: relative;
            overflow: hidden;
            padding: 15mm;
            box-sizing: border-box;
        }

        .certificate-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            height: 100%;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(59, 130, 246, 0.05);
            font-weight: bold;
            z-index: 1;
            pointer-events: none;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo-container img {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }

        .school-info {
            text-align: left;
        }

        .school-info h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
        }

        .school-info p {
            font-size: 14px;
            margin: 0;
        }

        .certificate-title {
            border: 2px solid #3b82f6;
            padding: 10px;
            margin-bottom: 20px;
            background: white;
        }

        .certificate-title h2 {
            font-size: 24px;
            color: #2563eb;
            margin: 0 0 5px 0;
        }

        .certificate-title p {
            font-size: 16px;
            margin: 0;
        }

        .student-info {
            text-align: center;
            margin: 20px 0;
        }

        .student-name {
            background: white;
            padding: 15px;
            border: 2px solid #bfdbfe;
            margin: 15px 0;
        }

        .student-name h3 {
            font-size: 20px;
            color: #1e40af;
            margin: 0 0 5px 0;
        }

        .notes {
            background: #fffbeb;
            border-left: 4px solid #fbbf24;
            padding: 10px;
            margin: 15px 0;
        }

        .notes h5 {
            font-size: 14px;
            color: #92400e;
            margin: 0 0 5px 0;
        }

        .notes p {
            font-size: 12px;
            color: #b45309;
            margin: 0;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .signature-box {
            text-align: center;
            flex: 1;
        }

        .signature-line {
            border-bottom: 1px solid #374151;
            width: 150px;
            margin: 5px auto;
        }

        .certificate-number {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="a4-page">
        <div class="certificate-bg">
            <!-- Watermark -->
            <div class="watermark">DITERIMA</div>

            <!-- Header -->
            <div class="header">
                <div class="logo-container">
                    @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo Pesantren">
                    @endif
                    <div class="school-info">
                        <h1>{{ $template->nama_pesantren }}</h1>
                        <p>{{ $template->nama_yayasan }}</p>
                        <p>{{ $template->alamat_pesantren }}</p>
                        <p>{{ $template->nomor_telepon }} | {{ $template->email_pesantren }}</p>
                    </div>
                </div>

                <div class="certificate-title">
                    <h2>SERTIFIKAT PENERIMAAN</h2>
                    <p>SANTRI BARU PERIODE PENDAFTARAN {{ $periode->nama_periode ?? date('Y') }}</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="student-info">
                <p>Dengan ini menyatakan bahwa:</p>
                
                <div class="student-name">
                    <h3>{{ $santri->nama_lengkap }}</h3>
                    <p>NISN : {{ $santri->nisn }}</p>
                    <p>Telah diterima sebagai Santri Baru</p>
                </div>

                <p>
                    Telah <strong style="color: #059669;">DITERIMA</strong> dan terdaftar sebagai santri baru di 
                    <strong style="color: #2563eb;">{{ $template->nama_pesantren }}</strong> untuk mengikuti pendidikan 
                    pada Tahun {{ $periode->nama_periode ?? date('Y') }}
                </p>
            </div>

            <!-- Notes -->
            <div class="notes">
                <h5>CATATAN PENTING:</h5>
                <p>{{ $template->catatan_penting }}</p>
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature-box">
                    <p>{{ $acceptanceDate }}</p>
                </div>

                <div class="signature-box">
                    <p>Direktur Pesantren</p>
                    <div class="signature-line"></div>
                    <p><strong>{{ $template->nama_direktur }}</strong></p>
                    <p>NIP: {{ $template->nip_direktur }}</p>
                </div>

                <div class="signature-box">
                    <p>Kepala Administrasi</p>
                    <div class="signature-line"></div>
                    <p><strong>{{ $template->nama_kepala_admin }}</strong></p>
                    <p>NIP: {{ $template->nip_kepala_admin }}</p>
                </div>
            </div>

            <!-- Certificate Number -->
            <div class="certificate-number">
                <p>
                    No. Sertifikat: <strong>{{ $certificateNumber }}</strong> | 
                    Tanggal Terbit: {{ $issueDate }}
                </p>
            </div>
        </div>
    </div>
</body>
</html> 