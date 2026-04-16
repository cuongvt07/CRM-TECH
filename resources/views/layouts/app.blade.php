<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'ERP System' }}</title>
        <!-- Font Awesome 6.5.2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        <script>
            // Tự động bôi đen nội dung khi focus vào các ô nhập liệu số (Áp dụng toàn hệ thống)
            document.addEventListener('focusin', function(e) {
                if (e.target.tagName === 'INPUT' && (e.target.type === 'number' || e.target.classList.contains('num-select'))) {
                    e.target.select();
                }
            });
        </script>
    </body>
</html>
