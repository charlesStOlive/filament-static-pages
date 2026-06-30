<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Site en maintenance' }}</title>
    @vite(['resources/css/front/front.css', 'resources/js/front/front.js'])
</head>
<body class="bg-primary-200">
    <div class="min-h-screen flex items-center justify-center p-4 ">
       {{ $slot }}
    </div>
</body>
</html>