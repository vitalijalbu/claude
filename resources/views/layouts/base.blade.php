<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="shadcn" class="scroll-pt-[5.7rem] scroll-smooth">
<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-[#F2EEE6]">
    <main id="app">
    {{-- Skip to main content link --}}
    <a class="sr-only" href="#main-content">Skip to main content</a>
    {{-- Header --}}
    @include('shared.partials.header')

    {{-- Main content --}}
    <main id="app">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('shared.partials.footer')
    </main>

    {{-- Scripts --}}
</body>
</html>