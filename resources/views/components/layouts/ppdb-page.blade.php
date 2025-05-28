<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      PPDB SMA Bina Prestasi - Wujudkan Potensi Terbaik Putra-Putri Anda
    </title>
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
  <body class="bg-white text-gray-800">
    
  {{ $slot }}

    <script>
      // Mobile Menu Toggle
      document
        .getElementById("mobile-menu-button")
        .addEventListener("click", function () {
          const mobileMenu = document.getElementById("mobile-menu");
          mobileMenu.classList.toggle("hidden");
        });

      // FAQ Toggle
      function toggleFAQ(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector("i");

        content.classList.toggle("hidden");
        icon.classList.toggle("fa-chevron-down");
        icon.classList.toggle("fa-chevron-up");
      }

      // Smooth Scrolling
      document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute("href"));
          if (target) {
            target.scrollIntoView({
              behavior: "smooth",
              block: "start",
            });
          }
        });
      });

      // Back to Top Button
      const backToTopButton = document.getElementById("backToTop");

      window.addEventListener("scroll", function () {
        if (window.pageYOffset > 300) {
          backToTopButton.classList.remove("hidden");
        } else {
          backToTopButton.classList.add("hidden");
        }
      });

      backToTopButton.addEventListener("click", function () {
        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
      });

      // Navbar Background on Scroll
      window.addEventListener("scroll", function () {
        const navbar = document.querySelector("nav");
        if (window.pageYOffset > 50) {
          navbar.classList.add("bg-white", "shadow-lg");
        } else {
          navbar.classList.remove("shadow-lg");
        }
      });
    </script>
  </body>
</html>
