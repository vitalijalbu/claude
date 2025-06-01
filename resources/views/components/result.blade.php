@props([
    'title' => 'Nessun risultato trovato',
    'description' => 'Prova a modificare i filtri di ricerca o cerca in una località diversa.',
    'buttonText' => 'Nuova ricerca',
    'buttonLink' => '/search',
])

<div class="text-center py-16">
    <h3 class="text-2xl font-medium mb-4">{{ $title }}</h3>
    <p class="text-base-content/70 mb-8">
        {{ $description }}
    </p>
    @if($buttonText && $buttonLink)
        <a href="{{ $buttonLink }}" class="btn btn-primary">
            {{ $buttonText }}
        </a>
    @endif
</div>
