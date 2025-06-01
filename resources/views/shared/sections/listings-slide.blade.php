<section class="container py-10">
    <x-page-header title="{{ __('site.listings_may_like') }}" />
    <div class="swiper carousel-items">
        <div class="swiper-wrapper">
            @foreach ($data as $listing)
                <div class="swiper-slide">
                    <x-listings.card :data="$listing"/>
                </div>
            @endforeach
        </div>
    </div>
</section>
