<!DOCTYPE html>
<html lang="it">
<head>
    <meta charSet="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OnlyEscort - Console</title>
    <link rel="icon" href="/images/favicon.ico" sizes="32x32" />
    <link rel="apple-touch-icon" href="/images/favicon.ico" />
    @viteReactRefresh 
    @vite('resources/js/app.jsx')
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
