<div wire:poll.5s="loadKehadiran">
    <div class="card shadow-sm h-100">
        <div class="card-body">
            <h2 class="card-title fw-bold mb-4">Jumlah Siswa Yang Hadir ({{ $jumlahHadir }} / {{ $totalSantri }} Hadir)</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Santri</th>
                            <th class="text-center">Jam Hadir</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($semuaSantri as $index => $santri)
                            @php
                                $detail = $daftarKehadiran->get($santri->id);
                                $currentStatus = $detail->status ?? 'Alpa';
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $santri->nama }}</td>
                                <td class="text-center">
                                    @if($detail && $currentStatus == 'Hadir' && $detail->jam_hadir)
                                        {{ \Carbon\Carbon::parse($detail->jam_hadir)->format('H:i:s') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @foreach(['Hadir', 'Izin', 'Sakit', 'Alpa'] as $status)
                                            <button 
                                                wire:click="updateStatus({{ $santri->id }}, '{{ $status }}')"
                                                class="btn {{ $currentStatus == $status ? ($status == 'Hadir' ? 'btn-success' : ($status == 'Izin' || $status == 'Sakit' ? 'btn-warning' : 'btn-danger')) : 'btn-outline-secondary' }}">
                                                {{ $status }}
                                            </button>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>