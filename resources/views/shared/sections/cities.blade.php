<section class="container py-10">
    <x-page-header
        title="{{__('site.cities_title')}}"
    />
    @if($data->isNotEmpty()) 
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            @foreach ($data as $city)
                @include('shared.snippets.city-card', ['data' => $city])
            @endforeach
        </div>
    @else
        <p>{{ __('site.no_data') }}</p> 
    @endif
</section>
