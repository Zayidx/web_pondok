<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Siswa - PPDB SMA Bina Prestasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      rel="stylesheet"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    @livewireStyles
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: "#1e40af",
              secondary: "#3b82f6",
              accent: "#f59e0b",
            },
          },
        },
      };
    </script>
    
  </head>
  <body class="bg-gray-50 text-gray-800">
    @yield('content')

    @livewireScripts
    <script>
      // Mobile Menu Toggle
      document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuButton = document.getElementById("mobile-menu-button");
        if (mobileMenuButton) {
          mobileMenuButton.addEventListener("click", function () {
            const mobileMenu = document.getElementById("mobile-menu");
            if (mobileMenu) {
              mobileMenu.classList.toggle("hidden");
            }
          });
        }

        // Listen for redirect event
        Livewire.on('redirect', (data) => {
          window.location.href = data.url;
        });
      });

      // Auto refresh status (simulasi)
      setInterval(function() {
        // Simulasi update status real-time
        console.log("Checking for status updates...");
      }, 30000); // Check every 30 seconds
    </script>
  </body>
</html>