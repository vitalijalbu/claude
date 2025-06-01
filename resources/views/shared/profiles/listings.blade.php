<section class="container py-10">
<x-page-header
    title="{{__('site.listings_spotlight_title')}}"
    subtitle="Get the best results using our sophisticated system and simple to use services."
    />
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach ($data as $listing)
            @include('shared.snippets.listing-card', ['data' => $listing])
        @endforeach
    </div>
</section>
