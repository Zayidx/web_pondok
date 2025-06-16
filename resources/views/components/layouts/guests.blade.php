<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sipondok') }} - Pendaftaran Santri</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Parsley.js for client-side validation -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .mandatory::after {
            content: ' *';
            color: red;
        }
        .text-danger {
            font-size: 0.875rem;
        }
    </style>

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="bg-light">
    <div class="container py-5">
        {{ $slot }}
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Parsley.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    
    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>