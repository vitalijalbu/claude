@props([
    'properties' => collect(),
    'viewType' => 'grid', // 'grid' or 'list'
])

<section class="py-8">
    <div class="container mx-auto px-4">
        @if (!empty($properties) && $properties->count() > 0)
            <!-- Grid View -->
            <div x-show="viewType === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($properties as $property)
                    @include('partials.property-card', ['property' => $property, 'viewType' => 'grid'])
                @endforeach
            </div>

            <!-- List View -->
            <div x-show="viewType === 'list'" class="space-y-6">
                @foreach ($properties as $property)
                    @include('partials.property-card', ['property' => $property, 'viewType' => 'list'])
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $properties->links() }}
            </div>
        @else
            <!-- No Results -->
            <x-result title="Nessuna villa trovata"
                description="Prova a modificare i filtri di ricerca o cerca in una località diversa."
                button-text="Nuova ricerca" button-link="/search" />
        @endif
    </div>
</section>
