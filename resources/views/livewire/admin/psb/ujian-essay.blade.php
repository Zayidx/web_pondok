<section>
    @if (session()->has('success'))
        <div class="d-flex justify-content-end">
            <div wire:poll class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Penilaian Essay - {{ \App\Models\Ujian::find($ujianId)->nama_ujian }}</h5>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Santri</th>
                        <th>Pertanyaan</th>
                        <th>Jawaban</th>
                        <th>Skor</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->listJawaban() as $jawaban)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jawaban->santri->nama_lengkap }}</td>
                            <td>{{ Str::limit($jawaban->soal->pertanyaan, 56, '...') }}</td>
                            <td>{{ Str::limit($jawaban->jawaban, 56, '...') }}</td>
                            <td>{{ $jawaban->skor ?? 'Belum dinilai' }}</td>
                            <td>{{ $jawaban->catatan ?? '-' }}</td>
                            <td>
                                <button wire:click='edit("{{ $jawaban->id }}")' data-bs-toggle="modal"
                                    data-bs-target="#penilaianEssay"
                                    class="btn btn-warning btn-sm">Nilai</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada jawaban essay!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" wire:ignore.self id="penilaianEssay" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit.prevent='updateJawaban'>
                    <div class="modal-header">
                        <h5 class="modal-title">Penilaian Essay</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Skor</label>
                            <input type="number" class="form-control" wire:model.live="skor">
                            @error('skor')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" wire:model.live="catatan"></textarea>
                            @error('catatan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>