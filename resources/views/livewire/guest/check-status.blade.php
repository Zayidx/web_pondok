<div class="container-fluid bg-white">
    <div class="mt-5">
        <div class="card bg-white shadow">
            <div class="card-header bg-success">
                <h4 class="text-white">Cek Status Pendaftaran Santri</h4>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="checkStatus" class="mt-5">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" wire:model="nisn" placeholder="Masukkan NISN (10 digit)" maxlength="10">
                        <button class="btn btn-success" type="submit">Cek Status</button>
                    </div>
                    @error('nisn') <span class="text-danger">{{ $message }}</span> @enderror
                    @if ($errorMessage)
                        <div class="alert alert-danger">{{ $errorMessage }}</div>
                    @endif
                </form>
    
                @if ($santri)
                    <div class="mt-4">
                        @if ($santri->status_santri === 'diterima')
                            <div class="alert alert-success">
                                <h5>Selamat! Anda diterima.</h5>
                                @if ($interview)
                                    <p><strong>Jadwal Wawancara:</strong></p>
                                    <ul>
                                        <li>Tanggal: {{ $interview->tanggal_wawancara }}</li>
                                        <li>Jam: {{ $interview->jam_wawancara }}</li>
                                        <li>Mode: {{ ucfirst($interview->mode) }}</li>
                                        @if ($interview->mode === 'online')
                                            <li>Link: <a href="{{ $interview->link_online }}" target="_blank">{{ $interview->link_online }}</a></li>
                                        @else
                                            <li>Lokasi: {{ $interview->lokasi_offline }}</li>
                                        @endif
                                    </ul>
                                @else
                                    <p>Jadwal wawancara akan segera diumumkan.</p>
                                @endif
                            </div>
                        @elseif ($santri->status_santri === 'ditolak')
                            <div class="alert alert-danger">
                                <h5>Mohon maaf, Anda ditolak.</h5>
                                @if ($santri->reason_rejected)
                                    <p><strong>Alasan Penolakan:</strong> {{ $santri->reason_rejected }}</p>
                                @else
                                    <p>Silakan hubungi administrasi untuk informasi lebih lanjut.</p>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <h5>Pendaftaran Anda sedang diproses.</h5>
                                <p>Silakan cek kembali nanti.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>