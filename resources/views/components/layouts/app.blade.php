<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Dashboard Activity Tracker' }}</title>
    <x-theme-script />
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0652FD',
                        secondary: '#64748b',
                    }
                }
            }
        }
    </script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Smooth Dark Mode Transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
        
        /* Exclude certain elements from transition */
        img, svg, video, canvas, audio, iframe, embed, object {
            transition-property: opacity, transform;
        }
        
        /* Faster transitions for interactive elements */
        button, a, input, select, textarea {
            transition-duration: 200ms;
        }
        
        /* Smooth color transitions */
        html {
            transition: background-color 300ms ease-in-out, color 300ms ease-in-out;
        }
    </style>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased transition-colors duration-300 ease-in-out" 
      x-data="{ 
          sidebarOpen: false, 
          showContactModal: false, 
          contactSuccess: false,
          darkMode: document.documentElement.classList.contains('dark'),
          init() {
              window.addEventListener('theme:changed', (event) => this.darkMode = event.detail.theme === 'dark');
          },
          toggleDarkMode() {
              this.darkMode = window.toggleTheme() === 'dark';
          }
      }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <div
            data-scroll-container
            class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden bg-gray-100 dark:bg-gray-900 transition-colors duration-300 ease-in-out"
        >
            <!-- Header/Navigation -->
            @include('layouts.navigation')

            <!-- Main Content -->
            <main class="w-full flex-grow p-4 md:p-6 lg:p-8">
                {{ $slot }}
            </main>

            <!-- Scroll-to-top (global for authenticated pages) -->
            <x-scroll-to-top />
        </div>
    </div>

    <x-contact-support-modal />

    @livewireScripts
</body>
</html>