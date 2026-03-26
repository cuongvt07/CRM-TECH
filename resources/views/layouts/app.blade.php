<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'ERP System' }}</title>
        <!-- Use Tailwind CDN as fallback for dev without Node.js -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#1677FF',
                            success: '#52C41A',
                            warning: '#FAAD14',
                            error: '#FF4D4F',
                            neutral: '#8C8C8C',
                            surface: '#FFFFFF',
                        }
                    }
                }
            }
        </script>
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        @livewireStyles
    </head>
    <body class="bg-[#F5F5F5] text-gray-800">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <livewire:layout.sidebar />

            <!-- Main Content -->
            <div class="flex flex-col flex-1 w-full">
                <!-- Header -->
                <livewire:layout.header />

                <!-- Page Content -->
                <main class="h-full overflow-y-auto p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
