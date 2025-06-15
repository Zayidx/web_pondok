{{-- resources/views/psb/surat-penerimaan-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Penerimaan Santri Baru</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
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
            background: #ffffff;
            position: relative;
            overflow: hidden;
            border: 10px solid #3b82f6;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(59, 130, 246, 0.08);
            font-weight: bold;
            z-index: 1;
            pointer-events: none;
            white-space: nowrap;
        }
        .main-content-area {
            position: relative;
            z-index: 2;
            padding: 25mm 20mm;
        }
        .logo-school-info {
            border-bottom: 2px double #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .logo {
            float: left;
            width: 80px;
            height: 80px;
        }
        .logo img {
            width: 100%;
            height: 100%;
        }
        .school-text {
            text-align: center;
        }
        .school-text h1 {
            font-size: 18pt;
            font-weight: bold;
            color: #1a202c;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }
        .school-text h2 {
            font-size: 16pt;
            font-weight: normal;
            color: #4a5568;
            margin: 5px 0 0 0;
            padding: 0;
            text-transform: uppercase;
        }
        .school-text p {
            font-size: 10pt;
            color: #4a5568;
            margin: 2px 0 0 0;
            padding: 0;
        }
        .certificate-title-box {
            text-align: center;
            margin-bottom: 25px;
        }
        .certificate-title-box h3 {
            font-size: 14pt;
            text-decoration: underline;
            margin: 0;
            padding: 0;
        }
        .certificate-title-box p {
            font-size: 12pt;
            margin: 5px 0 0 0;
        }
        .content-block {
            margin-bottom: 20px;
            line-height: 1.6;
            text-align: justify;
        }
        .student-data-table {
            width: 100%;
            margin: 20px 0 20px 40px;
            border-collapse: collapse;
        }
        .student-data-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 11pt;
        }
        .student-data-table td:first-child {
            width: 30%;
        }
        .student-data-table td:nth-child(2) {
            width: 5%;
        }
        .important-notes {
            background-color: #f0f7ff;
            border: 1px dashed #a0c2e6;
            padding: 15px;
            margin-top: 25px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        .important-notes h5 {
            font-weight: bold;
            color: #1e40af;
            margin-top: 0;
            margin-bottom: 8px;
        }
        .important-notes ul {
            list-style-type: disc;
            padding-left: 20px;
            margin: 0;
        }
        .important-notes ul li {
            font-size: 10pt;
            color: #334155;
            line-height: 1.5;
            margin-bottom: 5px;
        }
        .signatures-area {
            margin-top: 40px;
            width: 100%;
        }
        .signature-block-right {
            width: 45%;
            float: right;
            text-align: center;
        }
        .signature-block-left {
            width: 45%;
            float: left;
            text-align: center;
            height: 150px;
            position: relative;
        }
        .posisi-stempel img {
            position: absolute;
            left: 50px;
            top: 20px;
            width: 120px;
            opacity: 0.8;
        }
        .signature-text {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 70px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="a4-page">
        <div class="watermark">DITERIMA</div>

        <div class="main-content-area">

            <div class="logo-school-info clearfix">
                <div class="logo">
                    @if(isset($settings->logo_base64) && $settings->logo_base64)
                        <img src="{{ $settings->logo_base64 }}" alt="Logo">
                    @endif
                </div>
                <div class="school-text">
                    <h1>{{ $settings->nama_yayasan ?? '' }}</h1>
                    <h2>{{ $settings->nama_pesantren ?? '' }}</h2>
                    <p>{{ $settings->alamat_pesantren ?? '' }}</p>
                    <p>Telepon: {{ $settings->telepon_pesantren ?? '-' }} | Email: {{ $settings->email_pesantren ?? '-' }}</p>
                </div>
            </div>

            <div class="certificate-title-box">
                <h3>SURAT PEMBERITAHUAN HASIL SELEKSI</h3>
                <p>Nomor: {{ $nomor_surat ?? 'PSB/001' }}</p>
            </div>

            <div class="content-block">
                <p>Assalamu'alaikum Warahmatullahi Wabarakatuh,</p>
                <p>Berdasarkan hasil seleksi Penerimaan Santri Baru (PSB) {{ $settings->nama_pesantren ?? '' }} Tahun Ajaran {{ $periode_pendaftaran ?? '' }}, dengan hormat kami memberitahukan bahwa calon santri dengan data sebagai berikut:</p>

                <table class="student-data-table">
                    <tr>
                        <td>Nama Lengkap</td>
                        <td>:</td>
                        <td><strong>{{ $santri->nama_lengkap ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Nomor Pendaftaran</td>
                        <td>:</td>
                        <td>{{ $santri->id ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Asal Sekolah</td>
                        <td>:</td>
                        <td>{{ $santri->asal_sekolah ?? '-' }}</td>
                    </tr>
                </table>

                <p style="text-align: center; font-size: 14pt; margin: 20px 0;">Dinyatakan: <strong>DITERIMA</strong></p>
                
                <p>Sebagai santri baru pada jenjang pendidikan <strong>{{ $jenjang_diterima ?? '-' }}</strong> di {{ $settings->nama_pesantren ?? '' }}.</p>
                <p>Selanjutnya, kami mengharapkan kehadiran Bapak/Ibu/Wali Santri untuk melakukan proses daftar ulang sesuai dengan jadwal dan persyaratan yang telah ditetapkan. Mohon perhatikan catatan penting di bawah ini.</p>
                <p>Demikian surat pemberitahuan ini kami sampaikan. Atas perhatiannya, kami ucapkan terima kasih.</p>
                <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
            </div>
            @if(isset($settings->catatan_penting) && !empty($settings->catatan_penting))
<div class="important-notes">
    <h5 style="margin: 0 0 10px 0;">CATATAN PENTING:</h5>
    <p style="margin: 0; padding: 0; line-height: 1.5;">
        {{-- Kode ini akan mengubah baris baru dari database menjadi tag <br> di HTML --}}
        {!! nl2br(e($settings->catatan_penting)) !!}
    </p>
</div>
@endif

            <div class="signatures-area clearfix">
                <div class="signature-block-left">
                    @if(isset($settings->stempel_base64) && $settings->stempel_base64)
                        <div class="posisi-stempel">
                            <img src="{{ $settings->stempel_base64 }}" alt="Stempel">
                        </div>
                    @endif
                </div>

                <div class="signature-block-right">
                    <p>{{ $tempat_terbit ?? 'Jakarta' }}, {{ $tanggal_terbit ?? '' }}</p>
                    <p>Direktur Pesantren,</p>
                    <p class="signature-text">{{ $settings->nama_direktur ?? '-' }}</p>
                    <p>NIP. {{ $settings->nip_direktur ?? '-' }}</p>
                </div>
            </div>

        </div>
    </div>
</body>
</html>