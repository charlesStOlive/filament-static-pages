<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Site en maintenance' }}</title>
    @vite(['resources/css/front.css', 'resources/js/front/index.js'])
</head>

<body class="bg-primary-200">
    <div class="min-h-screen flex items-center justify-center p-4">
        {{ $slot }}
    </div>
</body>

</html>
