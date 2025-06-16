<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Al-Wahhab - Pendidikan Islam Terpadu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#059669',
                        secondary: '#065f46',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-gray-800">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-primary">Al-Wahhab</h1>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#beranda" class="text-gray-700 hover:text-primary transition-colors">Beranda</a>
                        <a href="#tentang" class="text-gray-700 hover:text-primary transition-colors">Tentang</a>
                        <a href="#program" class="text-gray-700 hover:text-primary transition-colors">Program</a>
                        <a href="#kontak" class="text-gray-700 hover:text-primary transition-colors">Kontak</a>
                    </div>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-primary">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white shadow-lg">
                <a href="#beranda" class="block px-3 py-2 text-gray-700 hover:text-primary">Beranda</a>
                <a href="#tentang" class="block px-3 py-2 text-gray-700 hover:text-primary">Tentang</a>
                <a href="#program" class="block px-3 py-2 text-gray-700 hover:text-primary">Program</a>
                <a href="#kontak" class="block px-3 py-2 text-gray-700 hover:text-primary">Kontak</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="pt-16 bg-gradient-to-br from-primary/5 to-secondary/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Pondok <span class="text-primary">Al-Wahhab</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Membentuk generasi Qur'ani yang berakhlak mulia dan berprestasi
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#program" class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-secondary transition-colors font-medium">
                        Lihat Program
                    </a>
                    <a href="#kontak" class="border-2 border-primary text-primary px-8 py-3 rounded-lg hover:bg-primary hover:text-white transition-colors font-medium">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Tentang Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Pondok Al-Wahhab adalah lembaga pendidikan Islam yang berkomitmen untuk membentuk generasi yang beriman, bertakwa, dan berilmu.
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Pendidikan Berkualitas</h3>
                    <p class="text-gray-600">Kurikulum terpadu yang menggabungkan pendidikan agama dan umum dengan standar tinggi.</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Lingkungan Islami</h3>
                    <p class="text-gray-600">Suasana pondok yang kondusif untuk pembentukan karakter dan akhlak mulia.</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Prestasi Terbaik</h3>
                    <p class="text-gray-600">Track record yang membanggakan dalam berbagai kompetisi akademik dan non-akademik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="program" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Program Pendidikan</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Berbagai program unggulan yang dirancang untuk mengembangkan potensi santri secara optimal.
                </p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-semibold mb-3 text-primary">Tahfidz Al-Qur'an</h3>
                    <p class="text-gray-600 mb-4">Program menghafal Al-Qur'an dengan metode yang efektif dan menyenangkan.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Target 30 Juz</li>
                        <li>• Metode Talaqqi</li>
                        <li>• Pembinaan Tajwid</li>
                    </ul>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-semibold mb-3 text-primary">Pendidikan Formal</h3>
                    <p class="text-gray-600 mb-4">Kurikulum nasional yang terintegrasi dengan nilai-nilai Islam.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• SMP & SMA</li>
                        <li>• Kurikulum Merdeka</li>
                        <li>• Pembelajaran Modern</li>
                    </ul>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-semibold mb-3 text-primary">Bahasa Arab & Inggris</h3>
                    <p class="text-gray-600 mb-4">Program intensif bahasa untuk komunikasi global.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Conversation Class</li>
                        <li>• Grammar & Writing</li>
                        <li>• Sertifikasi Internasional</li>
                    </ul>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-semibold mb-3 text-primary">Kitab Kuning</h3>
                    <p class="text-gray-600 mb-4">Kajian mendalam kitab-kitab klasik Islam.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Fiqh & Ushul Fiqh</li>
                        <li>• Hadits & Tafsir</li>
                        <li>• Akidah & Akhlak</li>
                    </ul>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-semibold mb-3 text-primary">Teknologi & Sains</h3>
                    <p class="text-gray-600 mb-4">Pembelajaran teknologi modern dan sains terapan.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Komputer & Programming</li>
                        <li>• Robotika</li>
                        <li>• Laboratorium Sains</li>
                    </ul>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-semibold mb-3 text-primary">Ekstrakurikuler</h3>
                    <p class="text-gray-600 mb-4">Kegiatan pengembangan bakat dan minat santri.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Olahraga & Seni</li>
                        <li>• Jurnalistik</li>
                        <li>• Kewirausahaan</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Hubungi Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Siap bergabung dengan keluarga besar Pondok Al-Wahhab? Hubungi kami untuk informasi lebih lanjut.
                </p>
            </div>
            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Alamat</h3>
                                <p class="text-gray-600">Jl. Pendidikan No. 123, Kota Santri, Indonesia 12345</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Telepon</h3>
                                <p class="text-gray-600">+62 21 1234 5678</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                                <p class="text-gray-600">info@pondokalwahhab.ac.id</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Jam Operasional</h3>
                                <p class="text-gray-600">Senin - Jumat: 08:00 - 16:00<br>Sabtu: 08:00 - 12:00</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-8">
                    <form class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                            <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-3 px-6 rounded-lg hover:bg-secondary transition-colors font-medium">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <h3 class="text-2xl font-bold mb-4">Pondok Al-Wahhab</h3>
                    <p class="text-gray-300 mb-4">
                        Lembaga pendidikan Islam terpadu yang berkomitmen membentuk generasi Qur'ani yang berakhlak mulia dan berprestasi.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Program</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white transition-colors">Tahfidz Al-Qur'an</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pendidikan Formal</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Bahasa Arab & Inggris</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kitab Kuning</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Informasi</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white transition-colors">Pendaftaran</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Biaya Pendidikan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Fasilitas</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Berita</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; 2024 Pondok Al-Wahhab. Semua hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                // Close mobile menu if open
                mobileMenu.classList.add('hidden');
            });
        });

        // Form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Terima kasih! Pesan Anda telah dikirim. Kami akan segera menghubungi Anda.');
        });
    </script>
</body>
</html>