@extends('layouts.base')

@section('title', 'Home')
@section('meta_description', 'description')

@section('content')


{{$page->sections[0]->title}}

<!-- Hero Section -->
<section class="relative h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/hero-bg.jpg') }}')">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative z-10 flex items-center justify-center h-full">
        <div class="text-center text-white max-w-4xl mx-auto px-4">
            <h1 class="text-5xl lg:text-7xl font-light mb-8 leading-tight">
                {{ __('Summer lovin\'') }}
            </h1>
            <p class="text-xl lg:text-2xl mb-12 font-light leading-relaxed">
                {{ __('Spring is in the air and summer is just around the corner! Get into summer mode and book your dream holiday home under the sun.') }}
            </p>
            <a href="{{ route('properties.search') }}" class="inline-block bg-white text-black px-12 py-4 text-lg font-medium hover:bg-gray-100 transition duration-300">
                {{ __('Explore our homes') }}
            </a>
        </div>
    </div>
</section>

<!-- Search Bar Section -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <form action="{{ route('properties.search') }}" method="GET" class="bg-gray-50 p-8 rounded-lg shadow-lg">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                    <!-- Destination -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Destination') }}</label>
                        <input type="text" name="destination" placeholder="{{ __('Where would you like to go?') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-black focus:border-transparent">
                    </div>
                    
                    <!-- Check-in -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Check-in') }}</label>
                        <input type="text" name="checkin" id="checkin" placeholder="{{ __('Add date') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-black focus:border-transparent">
                    </div>
                    
                    <!-- Check-out -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Check-out') }}</label>
                        <input type="text" name="checkout" id="checkout" placeholder="{{ __('Add date') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-black focus:border-transparent">
                    </div>
                    
                    <!-- Guests -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Guests') }}</label>
                        <select name="guests" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-black focus:border-transparent">
                            <option value="">{{ __('Add guests') }}</option>
                            @for($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? __('guest') : __('guests') }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <button type="submit" class="bg-black text-white px-12 py-4 text-lg font-medium hover:bg-gray-800 transition duration-300">
                        {{ __('Search') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Featured Properties Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-light mb-6">{{ __('Iconic Collection') }}</h2>
            <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                {{ __('In our Iconic Collection, we bring together our most emblematic houses, the ones that have completely stolen our hearts, and that are, for the most part, available exclusively in our collection.') }}
            </p>
        </div>
        
        <!-- Swiper Carousel -->
        <div class="swiper featured-properties-swiper">
            <div class="swiper-wrapper">
                @foreach($featuredProperties as $property)
                <div class="swiper-slide">
                    <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition duration-300">
                        <div class="relative">
                            <img src="{{ $property->featured_image }}" alt="{{ $property->title }}" class="w-full h-80 object-cover">
                            <div class="absolute top-4 right-4">
                                <button class="bg-white bg-opacity-80 p-2 rounded-full hover:bg-opacity-100 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-medium mb-1">{{ $property->title }}</h3>
                                    <p class="text-gray-600">{{ $property->location }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center mb-1">
                                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $property->rating }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    {{ $property->bedrooms }} {{ __('bedrooms') }} • {{ $property->guests }} {{ __('guests') }}
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-light">€{{ number_format($property->price_per_night) }}</span>
                                    <span class="text-gray-600 text-sm ml-1">{{ __('per night') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination mt-8"></div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-4xl lg:text-5xl font-light mb-8">{{ __('Each house in our collection is a match between our criteria of excellence and a love at first sight.') }}</h2>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    {{ __('Our advisors help you with total transparency to find the perfect house that matches your every desire.') }}
                </p>
                <a href="{{ route('about') }}" class="inline-block border border-black text-black px-8 py-3 font-medium hover:bg-black hover:text-white transition duration-300">
                    {{ __('Learn more about us') }}
                </a>
            </div>
            <div>
                <img src="{{ asset('images/about-image.jpg') }}" alt="{{ __('About us') }}" class="w-full h-96 object-cover rounded-lg">
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-light mb-6">{{ __('Tailored experiences') }}</h2>
            <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                {{ __('When on holiday, every moment must be magical. Forget obligations, our teams organise your entirely tailor-made holiday and take care of everything.') }}
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center">
                <div class="mb-6">
                    <svg class="w-16 h-16 mx-auto text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-light mb-4">{{ __('Unique locations') }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ __('Discover exceptional properties in the most beautiful destinations around the world.') }}</p>
            </div>
            
            <div class="text-center">
                <div class="mb-6">
                    <svg class="w-16 h-16 mx-auto text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-light mb-4">{{ __('Personal concierge') }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ __('Our dedicated team takes care of every detail to make your stay unforgettable.') }}</p>
            </div>
            
            <div class="text-center">
                <div class="mb-6">
                    <svg class="w-16 h-16 mx-auto text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-light mb-4">{{ __('24/7 support') }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ __('Our advisors are available to assist you before, during and after your stay.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-black text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl lg:text-5xl font-light mb-6">{{ __('Would you like to rent your exceptional villa?') }}</h2>
        <p class="text-xl mb-8 opacity-90">{{ __('We would be delighted to know more.') }}</p>
        <a href="{{ route('contact') }}" class="inline-block border border-white text-white px-8 py-3 font-medium hover:bg-white hover:text-black transition duration-300">
            {{ __('Contact us') }}
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize date pickers
    $("#checkin, #checkout").datepicker({
        dateFormat: 'dd/mm/yy',
        minDate: 0,
        onSelect: function(selectedDate) {
            if (this.id === 'checkin') {
                $("#checkout").datepicker("option", "minDate", selectedDate);
            }
        }
    });
    
    // Initialize Swiper
    const swiper = new Swiper('.featured-properties-swiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        }
    });
});
</script>
@endpush


@endsection