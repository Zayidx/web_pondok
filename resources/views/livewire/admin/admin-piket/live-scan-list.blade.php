<div wire:poll.5s="checkScanStatus">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="fs-5 fw-bold mb-0">Santri yang Telah Scan</h3>
    </div>
    
    <div style="max-height: 200px; overflow-y: auto;">
        <table class="table table-sm">
            <tbody>
                @forelse($liveScans as $log)
                    <tr>
                        <td>{{ $log->santri->nama ?? 'Santri tidak ditemukan' }}</td>
                        <td class="text-end text-success fw-bold">{{ $log->created_at->format('H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-muted fst-italic">Belum ada santri yang scan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
