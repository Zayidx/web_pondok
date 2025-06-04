<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Siswa - PPDB SMA Bina Prestasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
      rel="stylesheet"
    />
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

    <script>
      // Mobile Menu Toggle
      document
        .getElementById("mobile-menu-button")
        .addEventListener("click", function () {
          const mobileMenu = document.getElementById("mobile-menu");
          mobileMenu.classList.toggle("hidden");
        });

      // Auto refresh status (simulasi)
      setInterval(function() {
        // Simulasi update status real-time
        console.log("Checking for status updates...");
      }, 30000); // Check every 30 seconds
    </script>
  </body>
</html>