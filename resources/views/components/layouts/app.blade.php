<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'ERP System' }}</title>
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="antialiased">
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
