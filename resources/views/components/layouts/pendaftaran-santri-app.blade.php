<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pendaftaran Santri' }}</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            transition: all 0.3s;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        /* Content area styles */
        .main-content {
            transition: all 0.3s;
        }

        /* Table styles */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Card hover effect */
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Button hover effects */
        .btn {
            transition: all 0.2s;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Modal backdrop */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Badge styles */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        /* Loading spinner */
        .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 0.2em;
        }

        /* Toast notifications */
        .toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1050;
        }
    </style>

    @livewireStyles
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" src="{{ asset('images/logo.png') }}" alt="Logo">
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('e-ppdb.dashboard') }}" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <div>
                            <button type="button" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button">
                                <span class="sr-only">Open user menu</span>
                                @php
                                    $santri = auth('santri')->user();
                                    $nama = $santri ? $santri->nama_lengkap : 'User';
                                @endphp
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($nama) }}" alt="{{ $nama }}">
                            </button>
                        </div>

                        <!-- Dropdown menu -->
                        <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu-dropdown">
                            @if($santri)
                                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                    <div class="font-medium">{{ $santri->nama_lengkap }}</div>
                                    <div class="text-gray-500 text-xs">{{ $santri->email }}</div>
                                </div>
                            @endif
                            <a href="{{ route('santri.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Dashboard</a>
                            <form method="POST" action="{{ route('logout-ppdb-santri') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Initialize Bootstrap tooltips and popovers -->
    <script>
        $(document).ready(function(){
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('[data-bs-toggle="popover"]').popover();
        });
    </script>

    @livewireScripts

    <!-- Custom Scripts -->
    <script>
        // Flash message handling
        window.addEventListener('showAlert', event => {
            alert(event.detail.message);
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                Livewire.emit('closeModal');
            }
        });

        // Prevent form submission on enter
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });
    </script>

    @push('scripts')
    <script>
        // Toggle dropdown menu
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');

        if (userMenuButton && userMenuDropdown) {
            userMenuButton.addEventListener('click', () => {
                userMenuDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (event) => {
                if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                    userMenuDropdown.classList.add('hidden');
                }
            });
        }
    </script>
    @endpush
</body>
</html> 