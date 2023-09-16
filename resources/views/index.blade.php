<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ًApplication</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="h-full">
    <div id="app"></div>
</body>
</html>
