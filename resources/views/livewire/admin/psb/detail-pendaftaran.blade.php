@extends('components.layouts.app')

@section('title', 'Detail Pendaftaran')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Pendaftaran Ulang</h3>
                <p class="text-subtitle text-muted">Detail informasi pendaftaran ulang santri.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Data Santri</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold" style="width: 150px;">Nama Lengkap</td>
                            <td>: {{ $registration->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">NISN</td>
                            <td>: {{ $registration->nisn }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Asal Sekolah</td>
                            <td>: {{ $registration->asal_sekolah }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tipe Pendaftaran</td>
                            <td>: {{ ucfirst($registration->tipe_pendaftaran) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status Santri</td>
                            <td>: 
                                @if($registration->status_santri === 'diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @elseif($registration->status_santri === 'daftar_ulang')
                                    <span class="badge bg-info">Daftar Ulang</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Data Pembayaran</h6>
                    @if($registration->bukti_pembayaran)
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">Bank Pengirim</td>
                                <td>: {{ $registration->bank_pengirim }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nama Pengirim</td>
                                <td>: {{ $registration->nama_pengirim }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tgl. Pembayaran</td>
                                <td>: {{ optional($registration->tanggal_pembayaran)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status</td>
                                <td>: 
                                    @if($registration->status_pembayaran === 'verified')
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @elseif($registration->status_pembayaran === 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-info">Menunggu Verifikasi</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Bukti Pembayaran</td>
                                <td>
                                    <a href="{{ route('admin.psb.lihat-bukti', $registration->id) }}" class="btn btn-info btn-sm" target="_blank">
                                        <i class="bi bi-file-earmark-image"></i> Lihat Bukti
                                    </a>
                                </td>
                            </tr>
                        </table>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Belum ada data pembayaran.
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 