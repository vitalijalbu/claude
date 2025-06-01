<section class="container py-10">
<x-page-header
    title="{{__('site.listings_spotlight_title')}}"
    />
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4">
        @foreach ($data as $listing)
            <x-listings.card :data="$listing"/>
        @endforeach
    </div>
</section>
