{{-- Main Form Container --}}
<div class="space-y-6">
    {{-- Question Type Selection --}}
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900">Tipe Soal</label>
        <select wire:model.live="tipe_soal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option value="pg">Pilihan Ganda</option>
            <option value="essay">Essay</option>
        </select>
        @error('tipe_soal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Question Text Input --}}
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900">Pertanyaan</label>
        <textarea wire:model="pertanyaan" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
        @error('pertanyaan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    {{-- Multiple Choice Options Section (Conditional) --}}
    @if($tipe_soal === 'pg')
        <div>
            {{-- Options Header with Add Button --}}
            <div class="flex justify-between items-center mb-4">
                <label class="block text-sm font-medium text-gray-900">Opsi Jawaban</label>
                <button type="button" wire:click="addOption" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    + Tambah Opsi
                </button>
            </div>

            {{-- Options List --}}
            <div class="space-y-4">
                @foreach($opsi as $index => $option)
                    {{-- Individual Option Input Group --}}
                    <div class="flex items-center space-x-4">
                        {{-- Option Letter Label --}}
                        <span class="text-sm font-medium">{{ chr($index + 65) }}.</span>
                        
                        {{-- Option Text Input --}}
                        <div class="flex-1">
                            <input type="text" wire:model="opsi.{{ $index }}.teks" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Teks opsi">
                            @error("opsi.{$index}.teks") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Option Score Input --}}
                        <div class="w-24">
                            <input type="number" wire:model="opsi.{{ $index }}.bobot" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Bobot" min="0">
                            @error("opsi.{$index}.bobot") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Remove Option Button (if more than minimum required) --}}
                        @if(count($opsi) > 2)
                            <button type="button" wire:click="removeOption({{ $index }})" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
            {{-- Options Validation Error --}}
            @error('opsi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    @endif
</div> 