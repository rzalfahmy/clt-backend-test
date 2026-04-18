<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CLT Toolbox') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space+grotesk:400,500,700|instrument+sans:400,500,600&display=swap" rel="stylesheet" />

        @include('partials.assets')
    </head>
    <body class="font-sans text-stone-100 antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
            <div>
                <a href="/">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-400 text-sm font-bold text-stone-950">CLT</div>
                        <div>
                            <p class="font-display text-lg font-bold text-white">CLT Toolbox</p>
                            <p class="text-xs text-stone-400">Layup manager</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="mt-6 w-full max-w-md overflow-hidden rounded-3xl border border-white/10 bg-white/5 px-6 py-6 shadow-2xl shadow-black/20 backdrop-blur">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
