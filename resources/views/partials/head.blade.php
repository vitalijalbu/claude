<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'AlbaLuxStay')</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- SEO Meta Tags --}}
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">
<meta name="google" content="nositelinkssearchbox">
<meta name="description" content="@yield('meta_description', 'demo')">
<meta name="author" content="AlbaLuxStay">
<meta name="keywords" content="@yield('meta_keywords', 'Italia, luxury villa')">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ url()->current() }}">

{{-- Hreflang (SEO per lingue e localizzazione) --}}
<link rel="alternate" hreflang="it-IT" href="https://www.onlyescort.vip">
<link rel="alternate" hreflang="fr-FR" href="https://www.onlyescort.vip/fr">
<link rel="alternate" hreflang="de-DE" href="https://www.onlyescort.vip/de">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('og_title', 'AlbaLuxStay - Trova le migliori escort nella tua città')">
<meta property="og:description" content="@yield('og_description', 'Scopri le migliori escort disponibili con annunci aggiornati.')">
<meta property="og:image" content="@yield('og_image', url('/images/default-og-image.jpg'))">
<meta property="og:locale" content="it_IT">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="@yield('twitter_title', 'AlbaLuxStay - Trova le migliori escort nella tua città')">
<meta name="twitter:description" content="@yield('twitter_description', 'Scopri le migliori escort disponibili con annunci aggiornati.')">
<meta name="twitter:image" content="@yield('twitter_image', url('/images/default-twitter-image.jpg'))">

{{-- Favicons --}}
<link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
<link rel="manifest" href="/images/favicon/site.webmanifest">
<meta name="theme-color" content="#ff2d20">

{{-- Fonts & Styles --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.20.1/cdn/themes/light.css" />
<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.20.1/cdn/shoelace-autoloader.js"></script>

@vite(['resources/css/app.css', 'resources/js/app.js'])


@yield('schema')
