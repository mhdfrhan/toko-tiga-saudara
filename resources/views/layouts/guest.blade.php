<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-neutral-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-neutral-50">
        
        <div class="w-full max-w-md mt-6 p-8 bg-white shadow-lg shadow-neutral-200 overflow-hidden rounded-xl">
            <div class="mb-6 flex justify-center">
                <a href="/" wire:navigate>
                    <img src="{{ asset('assets/logo.png') }}" alt="{{ config('app.name') }} Logo" class="h-16 w-auto" />
                </a>
            </div>
            {{ $slot }}
        </div>
    </div>

    @stack('scripts')
</body>

</html>
