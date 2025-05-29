<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Siswa - SMA Bina Prestasi</title>
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
  <body class="bg-gray-50 min-h-screen">

  {{ $slot }}

    <script>
      // Toggle Password Visibility
      function togglePassword() {
        const passwordInput = document.querySelector('input[name="password"]');
        const toggleIcon = document.getElementById("passwordToggle");

        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          toggleIcon.classList.remove("fa-eye");
          toggleIcon.classList.add("fa-eye-slash");
        } else {
          passwordInput.type = "password";
          toggleIcon.classList.remove("fa-eye-slash");
          toggleIcon.classList.add("fa-eye");
        }
      }

      // Show Forgot Password Modal
      function showForgotPassword() {
        document
          .getElementById("forgotPasswordModal")
          .classList.remove("hidden");
        document.getElementById("forgotPasswordModal").classList.add("flex");
      }

      // Close Forgot Password Modal
      function closeForgotPassword() {
        document.getElementById("forgotPasswordModal").classList.add("hidden");
        document.getElementById("forgotPasswordModal").classList.remove("flex");
      }

      // Login Form Submission
      document
        .getElementById("loginForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          // Simulate login process
          const email = this.email.value;
          const password = this.password.value;

          // Simple validation (in real app, this would be server-side)
          if (email && password) {
            // Hide login section
            document.querySelector("section").style.display = "none";

            // Show dashboard
            document
              .getElementById("dashboardPreview")
              .classList.remove("hidden");

            // Update student name if email is provided
            if (email.includes("@")) {
              const name = email.split("@")[0].replace(/[._]/g, " ");
              document.getElementById("studentName").textContent =
                name.charAt(0).toUpperCase() + name.slice(1);
            }
          } else {
            alert("Mohon lengkapi email dan password");
          }
        });

      // Forgot Password Form
      document
        .getElementById("forgotPasswordForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const email = this.reset_email.value;
          if (email) {
            alert("Link reset password telah dikirim ke " + email);
            closeForgotPassword();
          }
        });

      // Logout Function
      function logout() {
        // Hide dashboard
        document.getElementById("dashboardPreview").classList.add("hidden");

        // Show login section
        document.querySelector("section").style.display = "flex";

        // Reset form
        document.getElementById("loginForm").reset();
      }

      // Form validation styling
      document.querySelectorAll("input").forEach((input) => {
        input.addEventListener("blur", function () {
          if (this.hasAttribute("required") && !this.value.trim()) {
            this.classList.add("border-red-500");
          } else {
            this.classList.remove("border-red-500");
          }
        });
      });
    </script>
  </body>
</html>
