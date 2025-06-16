<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Ulang Santri - SMA Bina Prestasi</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

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

    <style>
        /* Custom styles to ensure Tailwind and Bootstrap work together */
        .btn-whatsapp {
            @apply inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150;
        }
    </style>

    @livewireStyles
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            <a href="{{ route('check-status') }}" class="flex items-center">
                                @if(file_exists(public_path('images/logo.png')))
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                                @else
                                    <x-application-logo class="h-8 w-auto text-primary" />
                                @endif
                                <span class="ml-3 text-xl font-semibold text-primary">SMA Bina Prestasi</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right Navigation -->
                    <div class="flex items-center">
                        <a href="https://wa.me/6285156156851" target="_blank" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                            <i class="fas fa-whatsapp mr-2"></i>
                            Bantuan
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
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
          }
        });
      });

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
    </script>
    @livewireScripts
</body>

</html> 