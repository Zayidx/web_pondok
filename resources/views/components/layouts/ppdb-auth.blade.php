<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB - Login</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
    
    <!-- Custom CSS -->
    <style>
        .auth-background {
            background-image: url("{{ asset('assets/images/bg/ppdb-bg.jpg') }}");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex">
        <!-- Left side - Login Form -->
        <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-white">
            @yield('content')
        </div>
        
        <!-- Right side - Background Image -->
        <div class="hidden lg:block lg:w-1/2">
            <div class="auth-background h-full"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/dark.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html> 