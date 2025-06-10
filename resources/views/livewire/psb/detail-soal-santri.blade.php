@extends('layouts.detail-soal')

@section('content')
<div class="card">
    <div class="header">
        <h2>Detail Soal dan Jawaban</h2>
        <div style="display: flex; align-items: center; gap: 20px;">
            <span class="total-poin">Total Poin: {{ $totalPoin }}</span>
            <button wire:click="saveNilai" class="btn btn-primary">
                Simpan Nilai
            </button>
        </div>
    </div>

    <div>
        @foreach($soalUjian as $index => $soal)
            <div class="soal-item">
                <div class="soal-header">
                    <span class="soal-number">Soal {{ $index + 1 }}</span>
                    <span class="soal-type {{ $soal->tipe_soal === 'pg' ? 'type-pg' : 'type-essay' }}">
                        {{ $soal->tipe_soal === 'pg' ? 'Pilihan Ganda' : 'Essay' }}
                    </span>
                </div>

                <div class="soal-content">
                    {!! $soal->pertanyaan !!}
                </div>

                @if($soal->tipe_soal === 'pg')
                    <div class="opsi-list">
                        @foreach(json_decode($soal->opsi, true) as $key => $option)
                            <div class="opsi-item">
                                <input type="radio" disabled 
                                    {{ $jawabanUjian->get($soal->id)?->jawaban === $key ? 'checked' : '' }}>
                                <span class="{{ $key === $soal->kunci_jawaban ? 'kunci-jawaban' : '' }}">
                                    {{ $key }}. {{ $option['teks'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="jawaban-santri">
                        <p style="font-weight: bold; margin-bottom: 8px;">Jawaban Santri:</p>
                        <p>{{ $jawabanUjian->get($soal->id)?->jawaban ?? 'Belum dijawab' }}</p>
                    </div>
                    
                    <div class="nilai-input">
                        <label style="font-weight: bold;">Nilai:</label>
                        <input type="number" 
                            wire:model="poinEssay.{{ $soal->id }}"
                            wire:blur="savePoinEssay({{ $soal->id }}, $event.target.value)"
                            wire:keydown.enter="savePoinEssay({{ $soal->id }}, $event.target.value)"
                            min="0" 
                            max="{{ $soal->poin }}">
                        <span class="nilai-info">/ {{ $soal->poin }} poin</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection 