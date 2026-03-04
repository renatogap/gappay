<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('policia.nome') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased">
        <div class="bg-gray-100 h-screen flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <img src="<?=asset('images/skeleton.jpg')?>" alt="Skeleton" class="w-96 mx-auto mb-4">
                <h1 class="text-3xl font-bold text-gray-800">GapPay Api</h1>
                <p class="text-gray-600">Instalado com sucesso</p>
                <p class="text-gray-600">Rentec Digital</p>
            </div>
        </div>
    </body>
</html>
