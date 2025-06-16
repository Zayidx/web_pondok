<!DOCTYPE html>
<html lang="id">
  <head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru - SMA Bina Prestasi</title>
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
    @livewireStyles
  </head>
  <body class="bg-gray-50">
{{ $slot }}
    <script>
      // Step Navigation
      function nextStep(step) {
        // Validate current step
        const currentStep = document.querySelector('[id^="step"]:not(.hidden)');
        const inputs = currentStep.querySelectorAll(
          "input[required], select[required], textarea[required]"
        );
        let isValid = true;

        inputs.forEach((input) => {
          if (!input.value.trim()) {
            input.classList.add("border-red-500");
            isValid = false;
          } else {
            input.classList.remove("border-red-500");
          }
        });

        if (!isValid) {
          alert("Mohon lengkapi semua field yang wajib diisi");
          return;
        }

        // Hide current step
        document
          .querySelectorAll('[id^="step"]')
          .forEach((el) => el.classList.add("hidden"));

        // Show next step
        document.getElementById(`step${step}`).classList.remove("hidden");

        // Update progress
        updateProgress(step);

        // Scroll to top
        window.scrollTo({ top: 0, behavior: "smooth" });
      }

      function prevStep(step) {
        // Hide current step
        document
          .querySelectorAll('[id^="step"]')
          .forEach((el) => el.classList.add("hidden"));

        // Show previous step
        document.getElementById(`step${step}`).classList.remove("hidden");

        // Update progress
        updateProgress(step);

        // Scroll to top
        window.scrollTo({ top: 0, behavior: "smooth" });
      }

      function updateProgress(step) {
        // Reset all steps
        for (let i = 1; i <= 3; i++) {
          const stepElement = document.querySelector(
            `.flex.items-center.justify-center .flex:nth-child(${
              i * 2 - 1
            }) div`
          );
          const stepText = document.querySelector(
            `.flex.items-center.justify-center .flex:nth-child(${
              i * 2 - 1
            }) span`
          );

          if (i <= step) {
            stepElement.className =
              "bg-primary text-white rounded-full w-10 h-10 flex items-center justify-center font-bold";
            stepText.className = "ml-2 text-primary font-semibold";
          } else {
            stepElement.className =
              "bg-gray-300 text-gray-600 rounded-full w-10 h-10 flex items-center justify-center font-bold";
            stepText.className = "ml-2 text-gray-600";
          }
        }
      }

      // File Upload Preview
      document.querySelectorAll('input[type="file"]').forEach((input) => {
        input.addEventListener("change", function () {
          const preview = document.getElementById(this.id + "_preview");
          if (this.files && this.files[0]) {
            preview.classList.remove("hidden");

            if (this.id === "pas_foto") {
              const reader = new FileReader();
              reader.onload = function (e) {
                preview.querySelector("img").src = e.target.result;
              };
              reader.readAsDataURL(input.files[0]);
            }
          }
        });
      });

      // Form Submission
      document
        .getElementById("registrationForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          // Generate registration number
          const regNumber =
            "PPDB2025" +
            String(Math.floor(Math.random() * 1000) + 1).padStart(3, "0");
          document.getElementById("registrationNumber").textContent = regNumber;

          // Show success modal
          document.getElementById("successModal").classList.remove("hidden");
          document.getElementById("successModal").classList.add("flex");
        });

      function closeModal() {
        document.getElementById("successModal").classList.add("hidden");
        document.getElementById("successModal").classList.remove("flex");
      }

      // Form validation styling
      document.querySelectorAll("input, select, textarea").forEach((input) => {
        input.addEventListener("blur", function () {
          if (this.hasAttribute("required") && !this.value.trim()) {
            this.classList.add("border-red-500");
          } else {
            this.classList.remove("border-red-500");
          }
        });
      });
    </script>    @livewireScripts
</body>
</html>
