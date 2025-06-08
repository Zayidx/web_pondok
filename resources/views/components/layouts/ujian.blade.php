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

        .modal-container {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    overflow-y: auto;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
.modal-container[style*="display: flex"] {
    opacity: 1;
}
.modal-backdrop {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    z-index: 900;
}
.modal-container[style*="display: flex"] .modal-backdrop {
    opacity: 1;
}
.modal-content {
    background-color: white;
    border-radius: 0.75rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    max-width: 28rem;
    width: 100%;
    padding: 1.5rem;
    transform: translateY(-50px);
    opacity: 0;
    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    z-index: 1000;
}
.modal-container[style*="display: flex"] .modal-content {
    transform: translateY(0);
    opacity: 1;
}

        .modal-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 4rem;
            width: 4rem;
            border-radius: 9999px;
            background-color: #fef9c3;
            margin: 0 auto 1rem;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .modal-message {
            color: #4b5563;
            margin-bottom: 1.5rem;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .modal-button {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .modal-button-cancel {
            background-color: #f3f4f6;
            color: #374151;
        }

        .modal-button-cancel:hover {
            background-color: #e5e7eb;
        }

        .modal-button-confirm {
            display: inline-flex;
            align-items: center;
            background-color: #16a34a;
            color: white;
        }

        .modal-button-confirm:hover {
            background-color: #15803d;
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    @livewireScripts
    
    <!-- Debugging Script -->
    <script>
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized');
        });

        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded');
        });

        window.addEventListener('load', () => {
            console.log('Window loaded');
        });
        window.addEventListener('livewire:load', () => {
        Livewire.on('update', () => {
            console.log('Jawaban Siswa:', @json($jawabanSiswa ?? []));
        });
    });
    </script>
 
    @stack('scripts')
</body>
</html>