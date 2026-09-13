<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
        <title>{{ config('app.name', 'KOTE SCHOOL SHOP') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f6f0eb] text-[#19181a] antialiased font-sans">
        <x-layout.splash-screen />
        <x-ui.toast />
        <x-logout-modal />

        <div class="min-h-screen flex flex-col">
            <x-layout.header />

            <main class="flex-1 px-4 py-8 sm:px-6 lg:px-10">
                @yield('content')
            </main>

            <x-layout.footer />
        </div>
    </body>
</html>
