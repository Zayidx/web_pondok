<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Penerimaan - {{ $data->nama_lengkap }}</title>
    <style>
        @page {
            margin: 20mm 20mm 20mm 20mm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #333;
        }

        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
        }

        .header-table td {
            padding: 5px;
            vertical-align: middle;
        }

        .logo {
            width: 75px;
            height: auto;
        }

        .kop-surat {
            text-align: center;
        }

        .kop-surat .nama-pesantren {
            font-size: 16pt;
            font-weight: bold;
        }

        .kop-surat .nama-yayasan {
            font-size: 14pt;
        }

        .kop-surat .alamat {
            font-size: 10pt;
        }

        .surat-title {
            text-align: center;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .surat-title h3 {
            font-size: 14pt;
            text-decoration: underline;
            margin: 0;
            padding: 0;
        }
        
        .surat-title p {
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }

        .data-table {
            width: 100%;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .data-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .catatan-penting {
            border: 1px solid black;
            padding: 10px 15px;
            margin-top: 30px;
            font-size: 11pt;
        }

    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 20%; text-align: center;">
                @if($settings->logo_base64)
                    <img src="{{ $settings->logo_base64 }}" alt="Logo" class="logo">
                @endif
            </td>
            <td style="width: 80%;" class="kop-surat">
                <div class="nama-pesantren">{{ strtoupper($settings->nama_pesantren) }}</div>
                <div class="nama-yayasan">{{ $settings->nama_yayasan }}</div>
                <div class="alamat">{{ $settings->alamat_pesantren }}</div>
                <div class="alamat">Telp: {{ $settings->telepon_pesantren }} | Email: {{ $settings->email_pesantren }}</div>
            </td>
        </tr>
    </table>

    <div class="surat-title">
        <h3>SURAT KETERANGAN DITERIMA</h3>
        <p>Nomor: {{ date('Ymd') }}/{{ str_pad($data->id, 4, '0', STR_PAD_LEFT) }}/PSB/SKD/{{ date('Y') }}</p>
    </div>

    <p>Dengan hormat,</p>
    <p>Pimpinan {{ $settings->nama_pesantren }} dengan ini menerangkan bahwa, berdasarkan hasil seleksi Penerimaan Santri Baru (PSB) yang telah dilaksanakan, calon santri dengan data di bawah ini:</p>
    
    <table class="data-table">
        <tr>
            <td style="width: 30%;">Nama Lengkap</td>
            <td style="width: 5%;">:</td>
            <td style="width: 65%; font-weight: bold;">{{ $data->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Nomor Induk Siswa Nasional (NISN)</td>
            <td>:</td>
            <td>{{ $data->nisn }}</td>
        </tr>
         <tr>
            <td>Asal Sekolah</td>
            <td>:</td>
            <td>{{ $data->asal_sekolah }}</td>
        </tr>
    </table>

    <p>Telah dinyatakan <b>DITERIMA</b> sebagai santri baru di <b>{{ $settings->nama_pesantren }}</b> untuk Tahun Ajaran {{ $settings->tahun_ajaran }}.</p>
    <p>Demikian surat keterangan ini kami sampaikan untuk dapat dipergunakan sebagaimana mestinya.</p>
    

    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                <p>Kepala Administrasi,</p>
                <div style="height: 90px;">
                    <img src="../../../public/storage/surat-penerimaan/72VMotgVU12wJLTmhDUFCaWvOfs04rIkfZcixr9M.jpg" alt="">
                </div>
                <p style="font-weight: bold; text-decoration: underline; margin-top: 0;">{{ $settings->nama_kepala_admin }}</p>
                <p style="margin-top: -10px;">NIP: {{ $settings->nip_kepala_admin }}</p>
            </td>
            <!-- Kolom Tanda Tangan Direktur -->
            <td style="width: 50%;">
                <p>{{ $settings->kota_surat ?? 'Kota Anda' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Direktur Pesantren,</p>
                
                <div style="height: 90px; position: relative;">
                    @if($settings->ttd_direktur_base64)
                        <img src="{{ $settings->ttd_direktur_base64 }}" alt="ttd_direktur" style="max-width: 110px; opacity: 0.9; position: absolute; left: 50%; top: -10px; transform: translateX(-50%);">
                    @endif
                </div>

                <p style="font-weight: bold; text-decoration: underline; margin-top: 0;">{{ $settings->nama_direktur }}</p>
                <p style="margin-top: -10px;">NIP: {{ $settings->nip_direktur }}</p>
            </td>
        </tr>
    </table>

    @if(!empty($settings->catatan_penting))
        <div class="catatan-penting">
            <p style="margin:0 0 5px 0;"><strong>Catatan Penting:</strong></p>
            <div style="white-space: pre-wrap;">{!! nl2br(e($settings->catatan_penting)) !!}</div>
        </div>
    @endif
</body>
</html>
```

Saya telah memastikan semua informasi, termasuk nama kepala administrasi, NIP, dan ttd_direktur, ditampilkan dengan benar dalam tata letak dua kolom untuk tanda tang