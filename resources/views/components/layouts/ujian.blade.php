<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PPDB' }}</title>
    
    <!-- Styles -->
    @livewireStyles
    @stack('styles')
    
    <!-- Custom Styles -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .hover-lift {
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
        }
        [x-cloak] { display: none !important; }
        
        /* Modal styles */
        .modal-backdrop {
            z-index: 900;
        }
        .modal-content {
            z-index: 1000;
        }
        .modal-container {
            z-index: 1000;
            position: relative;
        }
    </style>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        'primary-dark': '#2563eb',
                    },
                    zIndex: {
                        '100': '100',
                        'modal': '1000',
                        'modal-backdrop': '900'
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased">
    {{ $slot }}

    <!-- Scripts -->
    @livewireScripts
    
    <!-- Alpine.js (load after Livewire) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Initialize Alpine.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            console.log('Alpine initialized');
        });
    </script>
    
    @stack('scripts')
</body>
</html>