<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Pesantren Al-Wahhab - Pendidikan Islam Terpadu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#16a085',
                        secondary: '#0d7377',
                        accent: '#27ae60',
                        gold: '#f39c12',
                        darkGreen: '#0f5132',
                        lightGreen: '#d4edda'
                    },
                    fontFamily: {
                        'arabic': ['Amiri', 'serif'],
                        'sans': ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body{
            overflow-x: hidden;
        }
        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a085' fill-opacity='0.05'%3E%3Cpath d='M30 30c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20zm0 0c0 11.046 8.954 20 20 20s20-8.954 20-20-8.954-20-20-20-20 8.954-20 20z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .mosque-silhouette {
            background: linear-gradient(135deg, #16a085 0%, #0d7377 100%);
            position: relative;
            overflow: hidden;
        }
        .mosque-silhouette::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120'%3E%3Cpath d='M600 60c-50-30-150-30-200 0-50 30-150 30-200 0-50-30-150-30-200 0v60h800V60c-50-30-150-30-200 0z' fill='%23ffffff' fill-opacity='0.1'/%3E%3C/svg%3E") repeat-x;
            background-size: 400px 100px;
        }
    </style>
</head>
<body class="bg-white text-gray-800 font-sans">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm shadow-lg fixed w-full top-0 z-50 border-b-2 border-primary/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-mosque text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-primary font-arabic">الوهاب</h1>
                            <p class="text-sm text-gray-600 font-medium">Al-Wahhab</p>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#beranda" class="text-gray-700 hover:text-primary transition-all duration-300 font-medium border-b-2 border-transparent hover:border-primary">Beranda</a>
                        <a href="#tentang" class="text-gray-700 hover:text-primary transition-all duration-300 font-medium border-b-2 border-transparent hover:border-primary">Tentang</a>
                        <a href="#program" class="text-gray-700 hover:text-primary transition-all duration-300 font-medium border-b-2 border-transparent hover:border-primary">Program</a>
                        <a href="#ppdb" class="text-gray-700 hover:text-primary transition-all duration-300 font-medium border-b-2 border-transparent hover:border-primary">PPDB</a>
                        <a href="#fasilitas" class="text-gray-700 hover:text-primary transition-all duration-300 font-medium border-b-2 border-transparent hover:border-primary">Fasilitas</a>
                        <a href="#kontak" class="bg-primary text-white px-6 py-2 rounded-full hover:bg-secondary transition-all duration-300 font-medium shadow-lg hover:shadow-xl">Hubungi Kami</a>
                    </div>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-primary transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white shadow-lg">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#beranda" class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-lightGreen rounded-md transition-all">Beranda</a>
                <a href="#tentang" class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-lightGreen rounded-md transition-all">Tentang</a>
                <a href="#program" class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-lightGreen rounded-md transition-all">Program</a>
                <a href="#ppdb" class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-lightGreen rounded-md transition-all">PPDB</a>
                <a href="#fasilitas" class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-lightGreen rounded-md transition-all">Fasilitas</a>
                <a href="#kontak" class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-lightGreen rounded-md transition-all">Kontak</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="pt-20 mosque-silhouette islamic-pattern min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white" data-aos="fade-right">
                    <div class="mb-6">
                        <p class="text-gold font-arabic text-2xl mb-2">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</p>
                        <p class="text-white/80 text-sm">Bismillahirrahmanirrahim</p>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                        Pondok Pesantren <span class="text-gold font-arabic">الوهاب</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-white/90 mb-8 leading-relaxed">
                        Membentuk Generasi Qur'ani yang Berakhlak Mulia, Berprestasi, dan Siap Menghadapi Tantangan Zaman
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#ppdb" class="bg-gold text-darkGreen px-8 py-4 rounded-full hover:bg-yellow-400 transition-all duration-300 font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <i class="fas fa-graduation-cap mr-2"></i>Daftar Santri Baru
                        </a>
                        <a href="#program" class="border-2 border-white text-white px-8 py-4 rounded-full hover:bg-white hover:text-primary transition-all duration-300 font-bold text-lg">
                            <i class="fas fa-book-open mr-2"></i>Lihat Program
                        </a>
                    </div>
                    <div class="mt-12 grid grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gold">25+</div>
                            <div class="text-white/80 text-sm">Tahun Berpengalaman</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gold">1000+</div>
                            <div class="text-white/80 text-sm">Alumni Sukses</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gold">A</div>
                            <div class="text-white/80 text-sm">Akreditasi</div>
                        </div>
                    </div>
                </div>
                <div class="relative" data-aos="fade-left" data-aos-delay="200">
                    <div class="relative z-10">
                        <img src="/homepage/santri_menunjuk.jpg" alt="Santri Berprestasi" class="rounded-2xl shadow-2xl w-full">
                        <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-xl shadow-xl" data-aos="zoom-in" data-aos-delay="500">
                            <div class="flex items-center">
                                <div class="bg-primary p-3 rounded-full mr-4">
                                    <i class="fas fa-award text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Akreditasi A</p>
                                    <p class="text-sm text-gray-600">Terakreditasi Unggul</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -top-6 -right-6 bg-gold p-6 rounded-xl shadow-xl" data-aos="zoom-in" data-aos-delay="700">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-darkGreen">100%</div>
                                <div class="text-sm text-darkGreen">Lulus UN</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center bg-lightGreen px-6 py-2 rounded-full mb-4">
                    <i class="fas fa-mosque text-primary mr-2"></i>
                    <span class="text-primary font-medium">Tentang Kami</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Pondok Pesantren Al-Wahhab</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Lembaga pendidikan Islam terpadu yang telah berdiri sejak 1998, berkomitmen membentuk generasi yang beriman, bertakwa, berilmu, dan berakhlak mulia.
                </p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-16 items-center mb-16">
                <div data-aos="fade-right">
                    <img src="/homepage/suasana_pondok.jpg" alt="Suasana Pondok" class="rounded-2xl shadow-xl w-full">
                </div>
                <div data-aos="fade-left">
                    <h3 class="text-3xl font-bold text-gray-900 mb-6">Visi & Misi Kami</h3>
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-xl font-semibold text-primary mb-3">
                                <i class="fas fa-eye mr-2"></i>Visi
                            </h4>
                            <p class="text-gray-600 leading-relaxed">
                                Menjadi pondok pesantren terdepan dalam mencetak generasi Qur'ani yang berakhlak mulia, berprestasi, dan mampu berkontribusi positif bagi masyarakat dan bangsa.
                            </p>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-primary mb-3">
                                <i class="fas fa-bullseye mr-2"></i>Misi
                            </h4>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-primary mr-3 mt-1"></i>
                                    Menyelenggarakan pendidikan Islam terpadu yang berkualitas
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-primary mr-3 mt-1"></i>
                                    Membina akhlak mulia berdasarkan Al-Qur'an dan As-Sunnah
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-primary mr-3 mt-1"></i>
                                    Mengembangkan potensi santri secara optimal dan holistik
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-primary mr-3 mt-1"></i>
                                    Mempersiapkan santri menghadapi tantangan global
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-8 bg-gradient-to-br from-lightGreen to-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-quran text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pendidikan Qur'ani</h3>
                    <p class="text-gray-600 leading-relaxed">Kurikulum berbasis Al-Qur'an dan As-Sunnah dengan metode pembelajaran modern dan efektif.</p>
                </div>
                <div class="text-center p-8 bg-gradient-to-br from-lightGreen to-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Lingkungan Islami</h3>
                    <p class="text-gray-600 leading-relaxed">Suasana pondok yang kondusif untuk pembentukan karakter dan akhlak mulia santri.</p>
                </div>
                <div class="text-center p-8 bg-gradient-to-br from-lightGreen to-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-trophy text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Prestasi Gemilang</h3>
                    <p class="text-gray-600 leading-relaxed">Track record membanggakan dalam berbagai kompetisi akademik dan non-akademik tingkat nasional.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="program" class="py-20 bg-gradient-to-br from-lightGreen/30 to-white islamic-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center bg-primary/10 px-6 py-2 rounded-full mb-4">
                    <i class="fas fa-book-open text-primary mr-2"></i>
                    <span class="text-primary font-medium">Program Unggulan</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Program Pendidikan Terpadu</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Berbagai program unggulan yang dirancang khusus untuk mengembangkan potensi santri secara optimal dan menyeluruh.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Program 1 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-quran text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Tahfidz Al-Qur'an</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Program menghafal Al-Qur'an 30 Juz dengan metode yang efektif dan menyenangkan, dibimbing oleh huffadz berpengalaman.</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Target 30 Juz</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Metode Talaqqi & Tasmi'</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Pembinaan Tajwid Intensif</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Muraja'ah Rutin</li>
                    </ul>
                    <div class="bg-lightGreen p-4 rounded-lg">
                        <p class="text-primary font-semibold text-sm">✨ Lulusan menjadi Huffadz Al-Qur'an</p>
                    </div>
                </div>

                <!-- Program 2 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-gradient-to-br from-accent to-primary rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pendidikan Formal</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Kurikulum nasional terintegrasi dengan nilai-nilai Islam, setara dengan SMP dan SMA umum.</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>SMP & SMA Islam Terpadu</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Kurikulum Merdeka</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Pembelajaran Modern</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Persiapan PTN/PTS</li>
                    </ul>
                    <div class="bg-lightGreen p-4 rounded-lg">
                        <p class="text-primary font-semibold text-sm">🎓 100% Lulus Ujian Nasional</p>
                    </div>
                </div>

                <!-- Program 3 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-gradient-to-br from-gold to-accent rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-globe text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Bahasa Arab & Inggris</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Program intensif bahasa untuk komunikasi global dan pemahaman kitab-kitab klasik.</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Conversation Class</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Grammar & Writing</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Sertifikasi TOEFL/IELTS</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Bahasa Arab Fusha</li>
                    </ul>
                    <div class="bg-lightGreen p-4 rounded-lg">
                        <p class="text-primary font-semibold text-sm">🌍 Siap Berkomunikasi Global</p>
                    </div>
                </div>

                <!-- Program 4 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-darkGreen rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-book text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Kitab Kuning</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Kajian mendalam kitab-kitab klasik Islam dengan metode sorogan dan bandongan.</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Fiqh & Ushul Fiqh</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Hadits & Tafsir</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Akidah & Akhlak</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Nahwu & Sharaf</li>
                    </ul>
                    <div class="bg-lightGreen p-4 rounded-lg">
                        <p class="text-primary font-semibold text-sm">📚 Menguasai Ilmu Agama</p>
                    </div>
                </div>

                <!-- Program 5 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-accent rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-laptop-code text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Teknologi & Sains</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Pembelajaran teknologi modern dan sains terapan untuk menghadapi era digital.</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Komputer & Programming</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Robotika & AI</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Laboratorium Sains</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Digital Literacy</li>
                    </ul>
                    <div class="bg-lightGreen p-4 rounded-lg">
                        <p class="text-primary font-semibold text-sm">💻 Siap Era Digital</p>
                    </div>
                </div>

                <!-- Program 6 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-gradient-to-br from-gold to-primary rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Ekstrakurikuler</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Kegiatan pengembangan bakat dan minat santri di berbagai bidang.</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Olahraga & Seni</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Jurnalistik & Media</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Kewirausahaan</li>
                        <li class="flex items-center"><i class="fas fa-check text-primary mr-2"></i>Pramuka & PMR</li>
                    </ul>
                    <div class="bg-lightGreen p-4 rounded-lg">
                        <p class="text-primary font-semibold text-sm">🎨 Pengembangan Bakat</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PPDB Section -->
    <section id="ppdb" class="py-20 bg-gradient-to-br from-primary to-secondary text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center bg-white/20 px-6 py-2 rounded-full mb-4">
                    <i class="fas fa-user-graduate text-gold mr-2"></i>
                    <span class="text-gold font-medium">PPDB 2025/2026</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Penerimaan Santri Baru</h2>
                <p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
                    Bergabunglah dengan keluarga besar Pondok Pesantren Al-Wahhab dan wujudkan impian menjadi generasi Qur'ani yang berprestasi!
                </p>
                <div class="mt-8 inline-flex items-center bg-gold text-darkGreen px-8 py-3 rounded-full font-bold text-lg">
                    <i class="fas fa-fire mr-2"></i>
                    Pendaftaran Dibuka Hingga 31 Maret 2025!
                </div>
            </div>

            <!-- Jadwal PPDB -->
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 mb-16" data-aos="fade-up">
                <h3 class="text-2xl font-bold text-center mb-8">Jadwal Penting PPDB 2025/2026</h3>
                <div class="grid md:grid-cols-4 gap-6">
                    <div class="text-center" data-aos="zoom-in" data-aos-delay="100">
                        <div class="bg-gold text-darkGreen rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                            1
                        </div>
                        <h4 class="font-bold mb-2">Pendaftaran</h4>
                        <p class="text-gold font-semibold">1 Jan - 31 Mar 2025</p>
                        <p class="text-sm text-white/80 mt-2">Daftar online & offline</p>
                    </div>
                    <div class="text-center" data-aos="zoom-in" data-aos-delay="200">
                        <div class="bg-gold text-darkGreen rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                            2
                        </div>
                        <h4 class="font-bold mb-2">Seleksi</h4>
                        <p class="text-gold font-semibold">5 - 10 Apr 2025</p>
                        <p class="text-sm text-white/80 mt-2">Tes & wawancara</p>
                    </div>
                    <div class="text-center" data-aos="zoom-in" data-aos-delay="300">
                        <div class="bg-gold text-darkGreen rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                            3
                        </div>
                        <h4 class="font-bold mb-2">Pengumuman</h4>
                        <p class="text-gold font-semibold">20 Apr 2025</p>
                        <p class="text-sm text-white/80 mt-2">Hasil kelulusan</p>
                    </div>
                    <div class="text-center" data-aos="zoom-in" data-aos-delay="400">
                        <div class="bg-gold text-darkGreen rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                            4
                        </div>
                        <h4 class="font-bold mb-2">Daftar Ulang</h4>
                        <p class="text-gold font-semibold">25 Apr - 5 Mei 2025</p>
                        <p class="text-sm text-white/80 mt-2">Pembayaran & berkas</p>
                    </div>
                </div>
            </div>

            <!-- Syarat Pendaftaran -->
            <div class="grid lg:grid-cols-2 gap-12 mb-16">
                <div data-aos="fade-right">
                    <h3 class="text-3xl font-bold mb-6">Syarat Pendaftaran</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-gold mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Persyaratan Umum</h4>
                                <p class="text-white/80 text-sm">Lulusan SD/MI atau SMP/MTs dengan usia maksimal 15 tahun</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-gold mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Dokumen Lengkap</h4>
                                <p class="text-white/80 text-sm">Ijazah, SKHUN, Akta Kelahiran, KK, Pas Foto, Surat Sehat</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-gold mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Kemampuan Dasar</h4>
                                <p class="text-white/80 text-sm">Bisa membaca Al-Qur'an dan memiliki motivasi belajar tinggi</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-gold mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Kesehatan</h4>
                                <p class="text-white/80 text-sm">Sehat jasmani dan rohani, tidak memiliki penyakit menular</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-aos="fade-left">
                    <h3 class="text-3xl font-bold mb-6">Biaya Pendidikan</h3>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b border-white/20 pb-2">
                                <span>Biaya Pendaftaran</span>
                                <span class="font-bold text-gold">Rp 300.000</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/20 pb-2">
                                <span>Uang Pangkal</span>
                                <span class="font-bold text-gold">Rp 5.000.000</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/20 pb-2">
                                <span>SPP Bulanan</span>
                                <span class="font-bold text-gold">Rp 800.000</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/20 pb-2">
                                <span>Asrama & Makan</span>
                                <span class="font-bold text-gold">Rp 600.000</span>
                            </div>
                            <div class="bg-gold/20 p-4 rounded-lg mt-4">
                                <p class="text-gold font-semibold text-sm">
                                    <i class="fas fa-gift mr-2"></i>
                                    Tersedia program beasiswa untuk santri berprestasi dan kurang mampu
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="text-center" data-aos="fade-up">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="bg-gold text-darkGreen px-8 py-4 rounded-full hover:bg-yellow-400 transition-all duration-300 font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Online Sekarang
                    </a>
                    <a href="#" class="border-2 border-gold text-gold px-8 py-4 rounded-full hover:bg-gold hover:text-darkGreen transition-all duration-300 font-bold text-lg">
                        <i class="fas fa-download mr-2"></i>Download Brosur PPDB
                    </a>
                    <a href="#kontak" class="border-2 border-white text-white px-8 py-4 rounded-full hover:bg-white hover:text-primary transition-all duration-300 font-bold text-lg">
                        <i class="fas fa-phone mr-2"></i>Konsultasi PPDB
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Fasilitas Section -->
    <section id="fasilitas" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center bg-lightGreen px-6 py-2 rounded-full mb-4">
                    <i class="fas fa-building text-primary mr-2"></i>
                    <span class="text-primary font-medium">Fasilitas Lengkap</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Fasilitas Penunjang Pembelajaran</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Fasilitas modern dan lengkap untuk mendukung proses pembelajaran yang optimal dan nyaman.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="zoom-in">
                    <img src="/homepage/masjid.jpeg" alt="Masjid" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                        <p class="text-white font-semibold p-4">Masjid Utama</p>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="zoom-in" data-aos-delay="100">
                    <img src="/homepage/asrama.jpg" alt="Asrama" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                        <p class="text-white font-semibold p-4">Asrama Santri</p>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="zoom-in" data-aos-delay="200">
                    <img src="/homepage/ruang_kelas.jpg" alt="Ruang Kelas" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                        <p class="text-white font-semibold p-4">Ruang Kelas AC</p>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="zoom-in" data-aos-delay="300">
                    <img src="/homepage/perpustakaan_digital.webp" alt="Perpustakaan" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                        <p class="text-white font-semibold p-4">Perpustakaan Digital</p>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="zoom-in">
                    <img src="/homepage/lab_komputer.webp" alt="Lab Komputer" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                        <p class="text-white font-semibold p-4">Lab Komputer</p>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="zoom-in" data-aos-delay="100">
                    <img src="/homepage/lab_sains.jpg" alt="Lab Sains" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                        <p class="text-white font-semibold p-4">Lab Sains</p>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="zoom-in" data-aos-delay="200">
                    <img src="/homepage/lapangan_olahraga.jpg" alt="Lapangan Olahraga" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                        <p class="text-white font-semibold p-4">Lapangan Olahraga</p>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" data-aos="zoom-in" data-aos-delay="300">
                    <img src="/homepage/kantin.jpg" alt="Kantin" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                        <p class="text-white font-semibold p-4">Kantin Sehat</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gradient-to-br from-lightGreen/30 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center bg-primary/10 px-6 py-2 rounded-full mb-4">
                    <i class="fas fa-quote-left text-primary mr-2"></i>
                    <span class="text-primary font-medium">Testimoni</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Apa Kata Mereka?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Dengarkan pengalaman dan kesaksian dari alumni, santri, dan orang tua tentang Pondok Pesantren Al-Wahhab.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimoni 1 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300" data-aos="fade-up">
                    <div class="flex items-center mb-6">
                        <img src="/placeholder.svg?height=60&width=60" alt="Alumni" class="w-15 h-15 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Ahmad Fauzi, S.Pd.I</h4>
                            <p class="text-sm text-gray-600">Alumni 2018</p>
                            <p class="text-sm text-primary">Guru di Pesantren Gontor</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic leading-relaxed mb-4">
                        "Alhamdulillah, pendidikan di Al-Wahhab sangat berkesan. Selain hafal Al-Qur'an, saya juga dibekali ilmu agama yang mendalam dan akhlak yang baik. Sekarang saya bisa mengamalkan ilmu tersebut sebagai pendidik."
                    </p>
                    <div class="flex text-gold">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-6">
                        <img src="/placeholder.svg?height=60&width=60" alt="Orang Tua" class="w-15 h-15 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Hj. Siti Aminah</h4>
                            <p class="text-sm text-gray-600">Orang Tua Santri</p>
                            <p class="text-sm text-primary">Ibu dari Fatimah (Kelas 3 SMA)</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic leading-relaxed mb-4">
                        "Saya sangat bersyukur menitipkan anak saya di Al-Wahhab. Perubahan akhlak dan kedisiplinannya sangat terlihat. Ustadz dan ustadzahnya sangat perhatian dan sabar dalam mendidik."
                    </p>
                    <div class="flex text-gold">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center mb-6">
                        <img src="/placeholder.svg?height=60&width=60" alt="Santri" class="w-15 h-15 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Muhammad Rizki</h4>
                            <p class="text-sm text-gray-600">Santri Kelas 2 SMA</p>
                            <p class="text-sm text-primary">Juara Olimpiade Sains</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic leading-relaxed mb-4">
                        "Di Al-Wahhab, saya tidak hanya belajar agama tapi juga sains dan teknologi. Fasilitasnya lengkap dan guru-gurunya sangat kompeten. Alhamdulillah bisa juara olimpiade sains tingkat provinsi."
                    </p>
                    <div class="flex text-gold">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center bg-primary/10 px-6 py-2 rounded-full mb-4">
                    <i class="fas fa-phone text-primary mr-2"></i>
                    <span class="text-primary font-medium">Hubungi Kami</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Informasi & Konsultasi</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Siap bergabung dengan keluarga besar Pondok Pesantren Al-Wahhab? Hubungi kami untuk informasi lebih lanjut dan konsultasi.
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div data-aos="fade-right">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8">Informasi Kontak</h3>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Alamat Pondok</h4>
                                <p class="text-gray-600 leading-relaxed">
                                    Jl. Pendidikan Islam No. 123<br>
                                    Desa Santri, Kec. Barokah<br>
                                    Kabupaten Berkah, Jawa Barat 12345
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Telepon</h4>
                                <p class="text-gray-600">+62 21 1234 5678</p>
                                <p class="text-gray-600">+62 812 3456 7890 (WhatsApp)</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Email</h4>
                                <p class="text-gray-600">info@pondokalwahhab.ac.id</p>
                                <p class="text-gray-600">ppdb@pondokalwahhab.ac.id</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Jam Operasional</h4>
                                <p class="text-gray-600">Senin - Jumat: 08:00 - 16:00 WIB</p>
                                <p class="text-gray-600">Sabtu: 08:00 - 12:00 WIB</p>
                                <p class="text-gray-600">Minggu: Libur</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-8">
                        <h4 class="font-bold text-gray-900 mb-4">Ikuti Media Sosial Kami</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-pink-600 text-white rounded-full flex items-center justify-center hover:bg-pink-700 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center hover:bg-green-700 transition-colors">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8" data-aos="fade-left">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Kirim Pesan</h3>
                    <form class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors" placeholder="Masukkan nama lengkap">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                                <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors" placeholder="nama@email.com">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subjek</label>
                            <select id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                                <option value="">Pilih subjek</option>
                                <option value="ppdb">Informasi PPDB</option>
                                <option value="program">Program Pendidikan</option>
                                <option value="biaya">Biaya Pendidikan</option>
                                <option value="fasilitas">Fasilitas Pondok</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                            <textarea id="message" name="message" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors" placeholder="Tulis pesan Anda di sini..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary text-white py-4 px-6 rounded-lg hover:from-secondary hover:to-primary transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-darkGreen to-secondary text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <!-- Logo & Description -->
                <div class="col-span-2">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-gold to-accent rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-mosque text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold font-arabic">الوهاب</h3>
                            <p class="text-gold font-medium">Pondok Pesantren Al-Wahhab</p>
                        </div>
                    </div>
                    <p class="text-white/80 mb-6 leading-relaxed">
                        Lembaga pendidikan Islam terpadu yang berkomitmen membentuk generasi Qur'ani yang berakhlak mulia, berprestasi, dan siap menghadapi tantangan zaman dengan landasan iman dan takwa.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-gold transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-gold transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-gold transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-gold transition-colors">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-xl font-bold mb-6 text-gold">Menu Utama</h4>
                    <ul class="space-y-3">
                        <li><a href="#beranda" class="text-white/80 hover:text-gold transition-colors">Beranda</a></li>
                        <li><a href="#tentang" class="text-white/80 hover:text-gold transition-colors">Tentang Kami</a></li>
                        <li><a href="#program" class="text-white/80 hover:text-gold transition-colors">Program Pendidikan</a></li>
                        <li><a href="#ppdb" class="text-white/80 hover:text-gold transition-colors">PPDB 2025/2026</a></li>
                        <li><a href="#fasilitas" class="text-white/80 hover:text-gold transition-colors">Fasilitas</a></li>
                        <li><a href="#kontak" class="text-white/80 hover:text-gold transition-colors">Kontak</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-xl font-bold mb-6 text-gold">Kontak Kami</h4>
                    <div class="space-y-3 text-white/80">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gold mr-3 mt-1"></i>
                            <span class="text-sm">Jl. Pendidikan Islam No. 123, Desa Santri, Kab. Berkah, Jawa Barat</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gold mr-3"></i>
                            <span class="text-sm">+62 21 1234 5678</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fab fa-whatsapp text-gold mr-3"></i>
                            <span class="text-sm">+62 812 3456 7890</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gold mr-3"></i>
                            <span class="text-sm">info@pondokalwahhab.ac.id</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/20 pt-8 text-center">
                <p class="text-white/60 mb-4">
                    &copy; 2025 Pondok Pesantren Al-Wahhab. Semua hak cipta dilindungi undang-undang.
                </p>
                <p class="text-gold font-arabic text-lg">
                    وَمَا تَوْفِيقِي إِلَّا بِاللَّهِ ۚ عَلَيْهِ تَوَكَّلْتُ وَإِلَيْهِ أُنِيبُ
                </p>
                <p class="text-white/60 text-sm mt-2">
                    "Dan tidak ada taufik bagiku melainkan dengan (pertolongan) Allah. Kepada-Nya aku bertawakkal dan kepada-Nya aku kembali." (QS. Hud: 88)
                </p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="https://wa.me/6281234567890" class="bg-green-500 text-white p-4 rounded-full shadow-2xl hover:bg-green-600 transition-all duration-300 flex items-center justify-center group hover:scale-110">
            <i class="fab fa-whatsapp text-2xl"></i>
            <span class="absolute right-16 bg-gray-900 text-white px-3 py-2 rounded-lg text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                Chat WhatsApp
            </span>
        </a>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-6 left-6 bg-primary text-white p-3 rounded-full shadow-lg hover:bg-secondary transition-all duration-300 hidden z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

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

        // Back to top button
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('hidden');
            } else {
                backToTopButton.classList.add('hidden');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const name = formData.get('name');
            const phone = formData.get('phone');
            const email = formData.get('email');
            const subject = formData.get('subject');
            const message = formData.get('message');
            
            // Create WhatsApp message
            const whatsappMessage = `Assalamu'alaikum, saya ingin bertanya tentang Pondok Pesantren Al-Wahhab.

Nama: ${name}
Email: ${email}
Subjek: ${subject}
Pesan: ${message}

Terima kasih.`;
            
            // Open WhatsApp
            const whatsappUrl = `https://wa.me/6281234567890?text=${encodeURIComponent(whatsappMessage)}`;
            window.open(whatsappUrl, '_blank');
            
            // Show success message
            alert('Terima kasih! Anda akan diarahkan ke WhatsApp untuk melanjutkan percakapan.');
            
            // Reset form
            this.reset();
        });

        // Navbar background on scroll
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('bg-white/95');
                nav.classList.remove('bg-white/95');
            }
        });

        // Counter animation
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current) + (target === 100 ? '%' : '+');
            }, 20);
        }

        // Trigger counter animation when in view
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('[data-counter]');
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-counter'));
                        animateCounter(counter, target);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.addEventListener('DOMContentLoaded', () => {
            const heroStats = document.querySelector('#beranda .grid.grid-cols-3');
            if (heroStats) {
                observer.observe(heroStats);
            }
        });
    </script>
</body>
</html>