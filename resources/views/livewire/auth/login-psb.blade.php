<div>
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="index.html" class="flex items-center">
                        <img class="h-10 w-10" src="https://via.placeholder.com/40x40/1e40af/ffffff?text=SMA" alt="Logo SMA"/>
                        <div class="ml-3">
                            <h1 class="text-xl font-bold text-primary">SMA Bina Prestasi</h1>
                        </div>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('psb-page') }}" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="{{ route('register-santri') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition duration-300">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="bg-primary p-4 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-user text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Login Siswa</h2>
                <p class="text-gray-600">Masuk ke dashboard pendaftaran Anda</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8">
                @if($errorMessage)
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ $errorMessage }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <form wire:submit.prevent="login" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" 
                                   wire:model.live="email" 
                                   wire:keydown="resetError('email')"
                                   class="w-full pl-10 pr-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300" 
                                   placeholder="Masukkan email (Gmail)"/>
                        </div>
                        @error('email') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password (NISN)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   wire:model.live="nisn" 
                                   wire:keydown="resetError('nisn')"
                                   class="w-full pl-10 pr-4 py-3 border @error('nisn') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300" 
                                   placeholder="Masukkan NISN"/>
                        </div>
                        @error('nisn') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 flex items-center justify-center"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="login">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                        </span>
                        <span wire:loading wire:target="login">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Memproses...
                        </span>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        Belum punya akun?
                        <a href="register.html" class="text-primary font-semibold hover:underline">Daftar sekarang</a>
                    </p>
                </div>
            </div>

            <div class="bg-blue-50 rounded-lg p-6 text-center">
                <h4 class="font-semibold text-gray-900 mb-2">Butuh Bantuan?</h4>
                <p class="text-sm text-gray-600 mb-4">Tim PPDB siap membantu Anda</p>
                <div class="flex justify-center space-x-4">
                    <a href="https://wa.me/6281234567890" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-600 transition duration-300">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>
                    <a href="tel:+62211234567" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-600 transition duration-300">
                        <i class="fas fa-phone mr-2"></i>Telepon
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
