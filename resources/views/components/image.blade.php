@props([
    'src' => null,
    'alt' => '',
    'class' => 'w-full h-full object-cover object-center rounded',
    'widths' => [165, 360, 500, 720, 960, 1066, 1280, 1500, 1800],
    'lazyload' => true,
    'preload' => false,
])

@php
    $placeholder = asset('/images/placeholder.svg');
    $imageSrc = $src ? $src : $placeholder;
@endphp

<picture>
    <img 
        src="{{ $imageSrc }}" 
        alt="{{ $alt }}"
        class="{{ $class }}"
        srcset="{{ implode(', ', array_map(fn($w) => $imageSrc . " {$w}w", $widths)) }}"
        sizes="(max-width: 1800px) 100vw, 1800px"
        loading="{{ $lazyload ? 'lazy' : 'eager' }}"
        {{ $preload ? 'preload' : '' }}
    >
</picture>
