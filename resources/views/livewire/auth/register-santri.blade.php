<div>
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <img class="h-10 w-10" src="https://via.placeholder.com/40x40/1e40af/ffffff?text=SMA" alt="Logo SMA" />
                        <div class="ml-3">
                            <h1 class="text-xl font-bold text-primary">SMA Bina Prestasi</h1>
                        </div>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="{{ route('login-ppdb-santri') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition duration-300">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="bg-gradient-to-r from-primary to-blue-700 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">Pendaftaran Siswa Baru</h1>
            <p class="text-xl mb-6">Tahun Ajaran 2025/2026</p>
            <div class="bg-white bg-opacity-20 rounded-lg p-4 inline-block">
                <p class="text-lg font-semibold">📅 Pendaftaran Dibuka: 1 Januari - 31 Maret 2025</p>
            </div>
        </div>
    </section>

    <!-- Registration Closed Message -->
    @if(!$isRegistrationOpen)
        <section class="py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded text-center">
                    <h2 class="text-2xl font-bold">Pendaftaran Ditutup</h2>
                    <p>Pendaftaran untuk tahun ajaran 2025/2026 saat ini tidak tersedia. Silakan hubungi pihak sekolah untuk informasi lebih lanjut.</p>
                </div>
            </div>
        </section>
    @else
        <!-- Progress Steps -->
        <section class="bg-white py-8 border-b">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-center space-x-4 md:space-x-8">
                    <div class="flex items-center">
                        <div class="{{ $formPage == 1 ? 'bg-primary text-white' : 'bg-gray-300 text-gray-600' }} rounded-full w-10 h-10 flex items-center justify-center font-bold">1</div>
                        <span class="ml-2 {{ $formPage == 1 ? 'text-primary font-semibold' : 'text-gray-600' }}">Data Pribadi</span>
                    </div>
                    <div class="w-8 h-1 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="{{ $formPage == 2 ? 'bg-primary text-white' : 'bg-gray-300 text-gray-600' }} rounded-full w-10 h-10 flex items-center justify-center font-bold">2</div>
                        <span class="ml-2 {{ $formPage == 2 ? 'text-primary font-semibold' : 'text-gray-600' }}">Data Orang Tua</span>
                    </div>
                    <div class="w-8 h-1 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="{{ $formPage == 3 ? 'bg-primary text-white' : 'bg-gray-300 text-gray-600' }} rounded-full w-10 h-10 flex items-center justify-center font-bold">3</div>
                        <span class="ml-2 {{ $formPage == 3 ? 'text-primary font-semibold' : 'text-gray-600' }}">Upload Dokumen</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Registration Form -->
        <section class="py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                @if($successMessage)
                    <div id="successMessage" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2500)">
                        <p>{{ $successMessage }}</p>
                    </div>
                @endif

                @if($errors->has('submit'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <p>{{ $errors->first('submit') }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="submit" id="registrationForm" class="space-y-8">
                    <!-- Step 1: Data Pribadi Siswa -->
                    <div id="step1" class="{{ $formPage == 1 ? 'block' : 'hidden' }} bg-white rounded-lg shadow-lg p-8">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Data Pribadi Siswa</h2>
                            <p class="text-gray-600">Lengkapi data pribadi calon siswa dengan benar</p>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                <input type="text" wire:model="santriForm.nama_lengkap" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300" placeholder="Masukkan nama lengkap" />
                                @error('santriForm.nama_lengkap') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NISN *</label>
                                <input type="text" wire:model="santriForm.nisn" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300" placeholder="Nomor Induk Siswa Nasional" />
                                @error('santriForm.nisn') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir *</label>
                                <input type="text" wire:model="santriForm.tempat_lahir" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300" placeholder="Kota tempat lahir" />
                                @error('santriForm.tempat_lahir') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                                <input type="date" wire:model="santriForm.tanggal_lahir" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300" />
                                @error('santriForm.tanggal_lahir') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin *</label>
                                <select wire:model="santriForm.jenis_kelamin" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('santriForm.jenis_kelamin') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Agama *</label>
                                <select wire:model="santriForm.agama" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                                @error('santriForm.agama') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" wire:model="santriForm.email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300" placeholder="email@example.com" />
                                @error('santriForm.email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. HP/WhatsApp *</label>
                                <input type="tel" wire:model="santriForm.no_whatsapp" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="08xxxxxxxxxx" />
                                @error('santriForm.no_whatsapp') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                            <textarea wire:model="alamatForm.alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
                            @error('alamatForm.alamat') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Asal Sekolah *</label>
                                <input type="text" wire:model="santriForm.asal_sekolah" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Nama SMP/MTs asal" />
                                @error('santriForm.asal_sekolah') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lulus *</label>
                                <select wire:model="santriForm.tahun_lulus" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="">Pilih Tahun Lulus</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                                @error('santriForm.tahun_lulus') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program Pilihan *</label>
                            <div class="grid md:grid-cols-3 gap-4">
                                @foreach(['reguler' => 'Program Reguler', 'olimpiade' => 'Kelas Olimpiade', 'internasional' => 'Kelas Internasional'] as $value => $label)
                                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-300">
                                        <input type="radio" wire:model="santriForm.tipe_pendaftaran" value="{{ $value }}" class="text-primary focus:ring-primary" />
                                        <div class="ml-3">
                                            <div class="font-medium text-gray-900">{{ $label }}</div>
                                            <div class="text-sm text-gray-600">
                                                {{ $value == 'reguler' ? 'Kurikulum standar nasional' : ($value == 'olimpiade' ? 'Fokus sains dan matematika' : 'Bilingual curriculum') }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('santriForm.tipe_pendaftaran') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end mt-8">
                            <button type="button" wire:click="nextForm" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                                Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Data Orang Tua -->
                    <div id="step2" class="{{ $formPage == 2 ? 'block' : 'hidden' }} bg-white rounded-lg shadow-lg p-8">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Data Orang Tua/Wali</h2>
                            <p class="text-gray-600">Lengkapi data orang tua atau wali siswa</p>
                        </div>
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Data Ayah</h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ayah *</label>
                                    <input type="text" wire:model="waliForm.nama_ayah" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Nama lengkap ayah" />
                                    @error('waliForm.nama_ayah') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ayah *</label>
                                    <input type="text" wire:model="waliForm.pekerjaan_ayah" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Pekerjaan ayah" />
                                    @error('waliForm.pekerjaan_ayah') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ayah *</label>
                                    <select wire:model="waliForm.pendidikan_ayah" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                    @error('waliForm.pendidikan_ayah') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Penghasilan Ayah *</label>
                                    <select wire:model="waliForm.penghasilan_ayah" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="">Pilih Range Penghasilan</option>
                                        <option value="< 2 juta">< Rp 2.000.000</option>
                                        <option value="2-5 juta">Rp 2.000.000 - 5.000.000</option>
                                        <option value="5-10 juta">Rp 5.000.000 - 10.000.000</option>
                                        <option value="> 10 juta">> Rp 10.000.000</option>
                                    </select>
                                    @error('waliForm.penghasilan_ayah') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Data Ibu</h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ibu *</label>
                                    <input type="text" wire:model="waliForm.nama_ibu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Nama lengkap ibu" />
                                    @error('waliForm.nama_ibu') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ibu *</label>
                                    <input type="text" wire:model="waliForm.pekerjaan_ibu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Pekerjaan ibu" />
                                    @error('waliForm.pekerjaan_ibu') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ibu *</label>
                                    <select wire:model="waliForm.pendidikan_ibu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                    @error('waliForm.pendidikan_ibu') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP Orang Tua *</label>
                                    <input type="tel" wire:model="waliForm.no_telp_ibu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="08xxxxxxxxxx" />
                                    @error('waliForm.no_telp_ibu') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between mt-8">
                            <button type="button" wire:click="prevForm" class="bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-600 transition duration-300">
                                <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                            </button>
                            <button type="button" wire:click="nextForm" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                                Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Upload Dokumen -->
                    <div id="step3" class="{{ $formPage == 3 ? 'block' : 'hidden' }} bg-white rounded-lg shadow-lg p-8">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Upload Dokumen</h2>
                            <p class="text-gray-600">Upload dokumen persyaratan pendaftaran (Format: PDF, JPG, PNG. Max: 2MB)</p>
                        </div>
                        <div class="space-y-6">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-primary transition duration-300">
                                <div class="text-center">
                                    <i class="fas fa-camera text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pas Foto 3x4 *</h3>
                                    <p class="text-sm text-gray-600 mb-4">Background merah, format JPG/PNG</p>
                                    <input type="file" wire:model="pas_foto" accept="image/*" class="hidden" id="pas_foto" />
                                    <label for="pas_foto" class="bg-primary text-white px-6 py-2 rounded-lg cursor-pointer hover:bg-blue-700 transition duration-300">Pilih File</label>
                                    <div id="pas_foto_preview" class="mt-4 {{ $pas_foto ? 'block' : 'hidden' }}">
                                        <img src="{{ $pas_foto ? $pas_foto->temporaryUrl() : '' }}" class="mx-auto h-32 w-24 object-cover rounded-lg border" />
                                        <p class="text-sm text-green-600 mt-2">✓ File berhasil dipilih</p>
                                    </div>
                                    @error('pas_foto') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-primary transition duration-300">
                                <div class="text-center">
                                    <i class="fas fa-file-pdf text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Ijazah SMP/Sederajat *</h3>
                                    <p class="text-sm text-gray-600 mb-4">Scan ijazah yang sudah dilegalisir</p>
                                    <input type="file" wire:model="ijazah" accept=".pdf,image/*" class="hidden" id="ijazah" />
                                    <label for="ijazah" class="bg-primary text-white px-6 py-2 rounded-lg cursor-pointer hover:bg-blue-700 transition duration-300">Pilih File</label>
                                    <div id="ijazah_preview" class="mt-4 {{ $ijazah ? 'block' : 'hidden' }}">
                                        <p class="text-sm text-green-600">✓ File berhasil dipilih</p>
                                    </div>
                                    @error('ijazah') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-primary transition duration-300">
                                <div class="text-center">
                                    <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">SKHUN *</h3>
                                    <p class="text-sm text-gray-600 mb-4">Surat Keterangan Hasil Ujian Nasional</p>
                                    <input type="file" wire:model="skhun" accept=".pdf,image/*" class="hidden" id="skhun" />
                                    <label for="skhun" class="bg-primary text-white px-6 py-2 rounded-lg cursor-pointer hover:bg-blue-700 transition duration-300">Pilih File</label>
                                    <div id="skhun_preview" class="mt-4 {{ $skhun ? 'block' : 'hidden' }}">
                                        <p class="text-sm text-green-600">✓ File berhasil dipilih</p>
                                    </div>
                                    @error('skhun') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-primary transition duration-300">
                                <div class="text-center">
                                    <i class="fas fa-id-card text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Akta Kelahiran *</h3>
                                    <p class="text-sm text-gray-600 mb-4">Fotocopy akta kelahiran</p>
                                    <input type="file" wire:model="akta_kelahiran" accept=".pdf,image/*" class="hidden" id="akta_kelahiran" />
                                    <label for="akta_kelahiran" class="bg-primary text-white px-6 py-2 rounded-lg cursor-pointer hover:bg-blue-700 transition duration-300">Pilih File</label>
                                    <div id="akta_kelahiran_preview" class="mt-4 {{ $akta_kelahiran ? 'block' : 'hidden' }}">
                                        <p class="text-sm text-green-600">✓ File berhasil dipilih</p>
                                    </div>
                                    @error('akta_kelahiran') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-primary transition duration-300">
                                <div class="text-center">
                                    <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Kartu Keluarga *</h3>
                                    <p class="text-sm text-gray-600 mb-4">Fotocopy Kartu Keluarga</p>
                                    <input type="file" wire:model="kartu_keluarga" accept=".pdf,image/*" class="hidden" id="kartu_keluarga" />
                                    <label for="kartu_keluarga" class="bg-primary text-white px-6 py-2 rounded-lg cursor-pointer hover:bg-blue-700 transition duration-300">Pilih File</label>
                                    <div id="kartu_keluarga_preview" class="mt-4 {{ $kartu_keluarga ? 'block' : 'hidden' }}">
                                        <p class="text-sm text-green-600">✓ File berhasil dipilih</p>
                                    </div>
                                    @error('kartu_keluarga') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                            <label class="flex items-start">
                                <input type="checkbox" wire:model="terms" class="mt-1 text-primary focus:ring-primary" />
                                <span class="ml-3 text-sm text-gray-700">
                                    Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dibentangkan. Saya bersedia mengikuti seluruh proses seleksi dan mematuhi peraturan yang berlaku di SMA Bina Prestasi.
                                    <a href="#" class="text-primary hover:underline">Baca Syarat & Ketentuan</a>
                                </span>
                            </label>
                            @error('terms') <span class="text-red-600 text-sm">{{ $message }}</span> @endif
                        </div>
                        <div class="flex justify-between mt-8">
                            <button type="button" wire:click="prevForm" class="bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-600 transition duration-300">
                                <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                            </button>
                            <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition duration-300" wire:loading.attr="disabled">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Pendaftaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center mb-4">
                <img class="h-8 w-8 mr-3" src="https://via.placeholder.com/32x32/1e40af/ffffff?text=SMA" alt="Logo SMA" />
                <h3 class="text-lg font-semibold">SMA Bina Prestasi</h3>
            </div>
            <p class="text-gray-400 mb-4">Jl. Pendidikan No. 123, Jakarta Selatan 12345</p>
            <p class="text-gray-400 text-sm">© 2025 SMA Bina Prestasi. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Success Modal -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-100 {{ $showSuccessModal ? 'flex' : 'hidden' }} items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md mx-auto">
            <div class="text-center">
                <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pendaftaran Berhasil!</h3>
                <p class="text-gray-600 mb-6">
                    Terima kasih telah mendaftar. Kami akan mengirimkan informasi lebih lanjut melalui email dan WhatsApp yang telah Anda daftarkan.
                </p>
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <p class="text-sm text-blue-800">
                        <strong>No. Pendaftaran:</strong> <span id="registrationNumber">{{ $registrationNumber }}</span><br />
                        Simpan nomor ini untuk keperluan selanjutnya.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login-ppdb-santri') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">Login ke Dashboard</a>
                    <button wire:click="closeModal" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>