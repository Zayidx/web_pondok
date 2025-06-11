<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Penerimaan Santri Baru</title>
    <style>
        /* Penting: Untuk Dompdf, gunakan CSS inline atau di dalam tag <style>.
           CDN seperti TailwindCSS atau Google Fonts perlu ditangani secara khusus
           (misalnya diunduh/dikonversi ke base64) agar berfungsi offline di Dompdf.
           Untuk kesederhanaan, saya menggunakan gaya CSS dasar yang umum didukung. */

        @page {
            size: A4; /* Ukuran kertas A4 */
            margin: 0; /* Hapus margin default halaman */
        }

        body {
            font-family: 'DejaVu Sans', sans-serif; /* Font yang mendukung karakter Latin & non-Latin untuk Dompdf */
            margin: 40px; /* Margin konten dari tepi halaman */
            font-size: 11pt;
            color: #333;
            background-color: #f8fafc; /* Warna latar belakang ringan */
            -webkit-print-color-adjust: exact; /* Pastikan warna latar belakang dicetak */
            print-color-adjust: exact;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-school-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background-color: #2563eb; /* Warna biru */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 24px;
        }

        .logo svg {
            width: 48px;
            height: 48px;
            color: #ffffff;
        }

        .school-text {
            text-align: left;
        }

        .school-text h1 {
            font-size: 24px;
            font-weight: bold;
            color: #1a202c;
            margin: 0;
            padding: 0;
        }

        .school-text p {
            font-size: 18px;
            color: #4a5568;
            margin: 0;
            padding: 0;
        }

        .school-text .address {
            font-size: 14px;
            color: #a0aec0;
        }

        .line-separator {
            border-bottom: 2px solid #333;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .certificate-title-box {
            text-align: center;
            padding: 24px;
            margin-bottom: 32px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 8px;
            border: 2px solid #90cdf4; /* Warna border biru muda */
        }

        .certificate-title-box h2 {
            font-size: 36px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 8px;
        }

        .certificate-title-box p {
            font-size: 20px;
            color: #4a5568;
        }

        .content {
            margin-top: 20px;
            line-height: 1.8;
            text-align: justify;
        }

        .content p {
            margin-bottom: 10px;
        }

        .student-data-table {
            width: 100%;
            margin-top: 20px;
            margin-left: 50px; /* Indentasi */
            border-collapse: collapse;
        }

        .student-data-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .student-data-table td:first-child {
            width: 30%;
            font-weight: bold;
        }

        .status-text {
            font-weight: bold;
            color: #10b981; /* Hijau */
        }

        .school-name-text {
            font-weight: bold;
            color: #2563eb; /* Biru */
        }

        .important-notes {
            background-color: #fffbeb; /* Kuning sangat muda */
            border-left: 4px solid #fbbf24; /* Kuning */
            padding: 16px;
            margin-top: 24px;
            margin-bottom: 24px;
            border-radius: 8px;
        }

        .important-notes h5 {
            font-weight: bold;
            color: #92400e; /* Coklat kuning */
            margin-bottom: 8px;
        }

        .important-notes ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .important-notes ul li {
            font-size: 14px;
            color: #b45309;
            line-height: 1.5;
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
            align-items: flex-end; /* Menyusun elemen di bagian bawah */
            margin-top: 40px;
        }

        .signature-block {
            text-align: center;
            flex: 1; /* Agar blok tanda tangan merata */
            padding: 0 10px; /* Sedikit padding antar blok */
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
            margin: 0 auto 16px;
        }

        .seal::before {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid white;
        }
        .seal-text {
            color: white;
            text-align: center;
            font-weight: bold;
        }
        .seal-text div { font-size: 10px; }

        .signature-line {
            border-bottom: 2px solid #374151; /* Abu-abu gelap */
            width: 180px; /* Lebar garis tanda tangan */
            margin: 0 auto 16px;
        }

        .signature-text {
            font-weight: bold;
            color: #1f2937;
        }

        .nik-text {
            font-size: 14px;
            color: #4a5568;
        }

        .certificate-number-info {
            text-align: center;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #d1d5db; /* Garis abu-abu muda */
            font-size: 14px;
            color: #6b7280;
        }

        .certificate-number-info .font-bold {
            font-weight: bold;
            font-family: monospace; /* Font mono untuk nomor surat */
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(59, 130, 246, 0.05); /* Biru transparan */
            font-weight: bold;
            z-index: 1; /* Pastikan di belakang konten utama */
            pointer-events: none; /* Agar tidak bisa diklik */
            white-space: nowrap; /* Mencegah teks patah baris */
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="a4-page">
        <div class="watermark">DITERIMA</div>

        <div class="ornament-corner top-left"></div>
        <div class="ornament-corner top-right"></div>
        <div class="ornament-corner bottom-left"></div>
        <div class="ornament-corner bottom-right"></div>

        <div class="main-content-area">
            <div class="logo-school-info">
                <div class="logo">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.115 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
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
                <p>TAHUN AJARAN {{ $periode_pendaftaran }}</p>
            </div>

            <div class="content">
                <p>Assalamu'alaikum Warahmatullahi Wabarakatuh,</p>
                <p>Dengan hormat, kami sampaikan bahwa berdasarkan hasil seleksi Penerimaan Santri Baru Pondok Pesantren Al-Hikmah Tahun Ajaran {{ $periode_pendaftaran }}, Ananda dengan data sebagai berikut:</p>

                <table class="student-data-table">
                    <tr>
                        <td>Nama Lengkap</td>
                        <td>: <strong>{{ $santri->nama_lengkap ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Nomor Pendaftaran</td>
                        <td>: <strong>{{ $santri->no_pendaftaran ?? '-' }}</strong></td>
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
                        <td>: {{ $santri->jenjang->nama_jenjang ?? 'Tidak tersedia' }}</td>
                    </tr>
                    <tr>
                        <td>Dinyatakan Diterima</td>
                        <td>: Sebagai Santri Baru Pondok Pesantren Al-Hikmah pada jenjang **{{ $jenjang_diterima }}**.</td>
                    </tr>
                    <tr>
                        <td>Nama Wali</td>
                        <td>: {{ $santri->waliSantri->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Nomor Telepon Wali</td>
                        <td>: {{ $santri->waliSantri->nomor_telepon ?? '-' }}</td>
                    </tr>
                </table>

                <div class="closing">
                    <p>Selanjutnya, Ananda diharapkan untuk segera melakukan proses daftar ulang sesuai dengan jadwal dan persyaratan yang telah ditetapkan oleh panitia.</p>
                    <p>Demikian surat pemberitahuan ini kami sampaikan. Kami ucapkan selamat bergabung dan semoga Allah SWT senantiasa memberikan kemudahan dan keberkahan dalam menuntut ilmu di Pondok Pesantren Al-Hikmah.</p>
                    <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
                </div>
            </div>

            <div>
                <div class="important-notes">
                    <h5>CATATAN PENTING:</h5>
                    <ul>
                        <li>Orientasi santri baru dimulai tanggal 15 Juli 2024</li>
                        <li>Harap membawa Surat ini saat registrasi ulang</li>
                        <li>Pembayaran SPP semester pertama paling lambat 20 Juli 2024</li>
                        <li>Untuk informasi lebih lanjut hubungi bagian administrasi</li>
                    </ul>
                </div>

                <div class="signatures-area">
                    <div class="signature-block">
                        <p>Jakarta, {{ $acceptanceDate }}</p>
                        <div class="seal">
                            <div class="seal-text">
                                <div>PESANTREN</div>
                                <div>AL-HIKMAH</div>
                                <div>2024</div>
                            </div>
                        </div>
                        <p class="nik-text">Stempel Resmi</p>
                    </div>

                    <div class="signature-block">
                        <p>Direktur Pesantren</p>
                        <div class="signature-line"></div>
                        <p class="signature-text">Dr. H. Abdul Rahman, M.Pd</p>
                        <p class="nik-text">NIK: 1234567890123456</p>
                    </div>

                    <div class="signature-block">
                        <p>Kepala Administrasi</p>
                        <div class="signature-line"></div>
                        <p class="signature-text">Hj. Fatimah, S.Pd</p>
                        <p class="nik-text">NIK: 9876543210987654</p>
                    </div>
                </div>

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