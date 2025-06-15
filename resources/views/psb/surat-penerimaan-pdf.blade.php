<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Penerimaan Santri Baru</title>
    <style>
        /* CSS ini diinjeksi langsung ke PDF, Dompdf tidak bisa memuat CSS dari CDN */
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif; /* Fallback font untuk Dompdf */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            margin: 0;
            padding: 0;
            position: relative;
            font-size: 11pt;
            color: #333;
        }

        .a4-page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: #ffffff; /* Default background */
            position: relative;
            overflow: hidden;
            border: 10px solid #3b82f6; /* Contoh border */
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(59, 130, 246, 0.08); /* Opacity yang lebih terlihat */
            font-weight: bold;
            z-index: 1;
            pointer-events: none;
            white-space: nowrap;
        }

        .main-content-area {
            position: relative;
            z-index: 2;
            padding: 30mm 20mm; /* Padding untuk konten utama */
        }

        .logo-school-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 70px; /* Lebih kecil agar tidak terlalu besar */
            height: 70px;
            background-color: #2563eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0; /* Penting agar tidak menyusut */
        }

        /* SVG logo Anda di sini (jika menggunakan SVG) */
        .logo svg {
            width: 40px;
            height: 40px;
            color: #ffffff;
        }
        /* Atau jika logo adalah gambar PNG/JPG */
        .logo img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }


        .school-text {
            text-align: left;
            flex-grow: 1;
        }

        .school-text h1 {
            font-size: 20px;
            font-weight: bold;
            color: #1a202c;
            margin: 0;
            padding: 0;
        }

        .school-text p {
            font-size: 14px;
            color: #4a5568;
            margin: 0;
            padding: 0;
        }
        .school-text .address {
            font-size: 10px;
            color: #a0aec0;
        }


        .line-separator {
            border-bottom: 1px solid #eee;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .certificate-title-box {
            text-align: center;
            padding: 15px;
            margin-bottom: 30px;
            background-color: #f0f7ff; /* light blue background */
            border: 1px solid #a0c2e6; /* light blue border */
            border-radius: 5px;
        }

        .certificate-title-box h2 {
            font-size: 28px; /* Lebih kecil agar pas */
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .certificate-title-box p {
            font-size: 16px;
            color: #4a5568;
        }

        .content-block {
            margin-bottom: 20px;
            line-height: 1.6;
            text-align: justify;
        }

        .content-block p {
            margin-bottom: 10px;
        }

        .student-data-table {
            width: 100%;
            margin: 20px 0 20px 40px; /* Indentasi */
            border-collapse: collapse;
        }

        .student-data-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .student-data-table td:first-child {
            width: 35%;
            font-weight: bold;
        }

        .status-text {
            font-weight: bold;
            color: #10b981;
        }

        .school-name-text {
            font-weight: bold;
            color: #2563eb;
        }

        .important-notes {
            background-color: #fffbeb;
            border-left: 4px solid #fbbf24;
            padding: 15px;
            margin-top: 25px;
            margin-bottom: 25px;
            border-radius: 5px;
        }

        .important-notes h5 {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 8px;
        }

        .important-notes ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .important-notes ul li {
            font-size: 10pt; /* Ukuran font lebih kecil */
            color: #b45309;
            line-height: 1.4;
        }

        .important-notes ul li::before {
            content: '• ';
            color: #fbbf24;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }

        .signatures-area {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            width: 100%;
        }

        .signature-block {
            text-align: center;
            width: 30%; /* Sesuaikan lebar */
            padding: 0 5px;
            vertical-align: top;
        }

        .seal {
            width: 80px; /* Sesuaikan ukuran segel */
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.2);
            margin: 0 auto 10px;
        }

        .seal::before {
            content: '';
            position: absolute;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 1px solid white;
        }
        .seal-text {
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 8pt; /* Ukuran font segel */
            line-height: 1.1;
        }

        .signature-line {
            border-bottom: 1px solid #374151;
            width: 150px;
            margin: 0 auto 10px;
        }

        .signature-text {
            font-weight: bold;
            color: #1f2937;
            font-size: 10pt;
        }

        .nik-text {
            font-size: 8pt;
            color: #4a5568;
        }

        .certificate-number-info {
            text-align: right; /* Pindahkan ke kanan */
            margin-top: 20px;
            padding-top: 10px;
            border-top: 0.5px solid #d1d5db;
            font-size: 9pt;
            color: #6b7280;
        }

        .certificate-number-info .font-bold {
            font-weight: bold;
            font-family: monospace;
        }
    </style>
    </head>
<body>
    <div class="a4-page">
        <div class="watermark">DITERIMA</div>

        <div class="main-content-area">
            <div class="logo-school-info">
            <div class="logo">
    @if($settings->logo_base64)
        <img src="{{ $settings->logo_base64 }}" alt="Logo">
    @endif
</div>
                <div class="school-text">
                    <h1>PESANTREN AL-HIKMAH</h1>
                    <p>Yayasan Pendidikan Islam Terpadu</p>
                    <p class="address">Jl. Pendidikan No. 123, Jakarta Selatan 12345</p>
                    <p class="address">Telp: (021) 1234-5678 | Email: info@alhikmah.ac.id</p>
                </div>
            </div>

            <div class="line-separator"></div>

            <div class="certificate-title-box">
                <h2>SURAT PEMBERITAHUAN PENERIMAAN SANTRI BARU</h2>
                <p>TAHUN AJARAN {{ $periode_pendaftaran ?? '2025/2026' }}</p>
            </div>

            <div class="content-block">
                <p>Assalamu'alaikum Warahmatullahi Wabarakatuh,</p>
                <p>Dengan hormat, kami sampaikan bahwa berdasarkan hasil seleksi Penerimaan Santri Baru Pondok Pesantren Al-Hikmah Tahun Ajaran {{ $periode_pendaftaran ?? '2025/2026' }}, Ananda dengan data sebagai berikut:</p>

                <table class="student-data-table">
                    <tr>
                        <td>Nama Lengkap</td>
                        <td>: <strong>{{ $santri->nama_lengkap ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Nomor Pendaftaran</td>
                        <td>: <strong>{{ $santri->id ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>: {{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td>Asal Sekolah</td>
                        <td>: {{ $santri->asal_sekolah ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jenjang Pendidikan</td>
                        <td>: {{ $jenjang_diterima ?? 'SMA' }}</td>
                    </tr>
                    <tr>
                        <td>Dinyatakan Diterima</td>
                        <td>: Sebagai Santri Baru Pondok Pesantren Al-Hikmah pada jenjang <strong>{{ $jenjang_diterima ?? 'SMA' }}</strong>.</td>
                    </tr>
                    <tr>
                        <td>Nama Wali</td>
                        <td>: {{ $santri->wali->nama_ayah ?? $santri->wali->nama_ibu ?? $santri->wali->nama_wali ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Nomor Telepon Wali</td>
                        <td>: {{ $santri->wali->no_hp_ortu ?? $santri->wali->no_hp ?? '-' }}</td>
                    </tr>
                </table>

                <p>Selanjutnya, Ananda diharapkan untuk segera melakukan proses daftar ulang sesuai dengan jadwal dan persyaratan yang telah ditetapkan oleh panitia.</p>
                <p>Demikian surat pemberitahuan ini kami sampaikan. Kami ucapkan selamat bergabung dan semoga Allah SWT senantiasa memberikan kemudahan dan keberkahan dalam menuntut ilmu di Pondok Pesantren Al-Hikmah.</p>
                <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
            </div>

            <div class="important-notes">
                <h5>CATATAN PENTING:</h5>
                <ul>
                    <li>Orientasi santri baru dimulai tanggal 15 Juli 2024</li>
                    <li>Harap membawa Surat ini saat registrasi ulang</li>
                    <li>Pembayaran SPP semester pertama paling lambat 20 Juli 2024</li>
                    <li>Untuk informasi lebih lanjut hubungi bagian administrasi</li>
                </ul>
            </div>

            <table class="signatures-area">
                <tr>
                    <td class="signature-block">
                        <p>Jakarta, {{ $acceptanceDate }}</p>
                        <div class="seal">
                            <div class="seal-text">
                                <div>PESANTREN</div>
                                <div>AL-HIKMAH</div>
                                <div>2024</div>
                            </div>
                        </div>
                        @if($settings->stempel_base64)
    <img src="{{ $settings->stempel_base64 }}" alt="Stempel" style="width: 80px; height: 80px; position: absolute; left: 50%; transform: translateX(-50%); margin-top: 15px; opacity: 0.8;">
@endif

                    </td>

                    <td class="signature-block">
                        <p>Direktur Pesantren</p>
                        <div class="signature-line"></div>
                        <p class="signature-text">Dr. H. Abdul Rahman, M.Pd</p>
                        <p class="nik-text">NIK: 1234567890123456</p>
                    </td>

                    <td class="signature-block">
                        <p>Kepala Administrasi</p>
                        <div class="signature-line"></div>
                        <p class="signature-text">Hj. Fatimah, S.Pd</p>
                        <p class="nik-text">NIK: 9876543210987654</p>
                    </td>
                </tr>
            </table>

            <div class="certificate-number-info">
                <p>
                    No. Surat: <span class="font-bold">{{ $certificateNumber }}</span> |
                    Tanggal Terbit: {{ $issueDate }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>