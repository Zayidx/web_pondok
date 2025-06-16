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
            <h5 class="card-title">Ujian: {{ \App\Models\Ujian::find($ujianId)->nama_ujian }}</h5>
        </div>

        <div class="card-body">
            @if ($soal)
                <form wire:submit.prevent='submitJawaban'>
                    <div class="mb-3">
                        <label class="form-label">Soal {{ $currentSoalIndex + 1 }}</label>
                        <p>{{ $soal->pertanyaan }}</p>
                    </div>
                    @if ($soal->tipe_soal === 'pg')
                        <div class="mb-3">
                            @foreach (json_decode($soal->pilihan_jawaban, true) as $pilihan)
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" wire:model.live="jawaban"
                                        value="{{ $pilihan }}" required>
                                    <label class="form-check-label">{{ $pilihan }}</label>
                                </div>
                            @endforeach
                            @error('jawaban')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div class="mb-3">
                            <textarea class="form-control" wire:model.live="jawaban" required></textarea>
                            @error('jawaban')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        {{ $currentSoalIndex + 1 < $soals->count() ? 'Simpan & Lanjut' : 'Selesai' }}
                    </button>
                </form>
            @else
                <p class="text-center">Tidak ada soal untuk ujian ini.</p>
            @endif
        </div>
    </div>
</section>