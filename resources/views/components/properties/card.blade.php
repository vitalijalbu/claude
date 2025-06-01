@props([
    'property',
    'viewType' => 'grid'
])

@if($viewType === 'list')
    <!-- List View -->
    <div class="flex bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
        <div class="w-80 flex-shrink-0">
            @if($property->featured_image)
                <img src="{{ $property->featured_image }}" alt="{{ $property->title }}" class="w-full h-64 object-cover">
            @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                </div>
            @endif
        </div>
        <div class="flex-1 p-6">
            <div class="flex justify-between h-full">
                <div class="flex-1">
                    <div class="mb-2">
                        <h3 class="text-xl font-medium mb-1">
                            <a href="{{ route('properties.show', $property->slug ?? $property->id) }}" class="hover:underline">
                                {{ $property->title }}
                            </a>
                        </h3>
                        @if($property->location)
                        <p class="text-gray-600">{{ $property->location }}</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                        @if($property->guests)
                        <span>{{ $property->guests }} {{ __('guests') }}</span>
                        @endif
                        @if($property->bedrooms)
                        <span>{{ $property->bedrooms }} {{ __('bedrooms') }}</span>
                        @endif
                        @if($property->bathrooms)
                        <span>{{ $property->bathrooms }} {{ __('bathrooms') }}</span>
                        @endif
                        @if($property->rating)
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <span>{{ $property->rating }}</span>
                        </div>
                        @endif
                    </div>
                    
                    @if($property->description)
                    <p class="text-gray-700 leading-relaxed">{{ Str::limit(strip_tags($property->description), 200) }}</p>
                    @endif
                </div>
                
                <div class="flex flex-col justify-between items-end ml-6">
                    <x-wishlist-button :property="$property" />
                    
                    <div class="text-right">
                        @if($property->price_per_night)
                        <div class="text-2xl font-light">€{{ number_format($property->price_per_night) }}</div>
                        <div class="text-gray-600 text-sm">{{ __('per night') }}</div>
                        @else
                        <div class="text-lg font-light text-gray-500">{{ __('Price on request') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Grid View -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
        <div class="relative">
            @if($property->featured_image)
                <img src="{{ $property->featured_image }}" alt="{{ $property->title }}" class="w-full h-64 object-cover">
            @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                </div>
            @endif
            <div class="absolute top-4 right-4">
                <x-wishlist-button :property="$property" />
            </div>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <h3 class="text-xl font-medium mb-1">
                    <a href="{{ route('properties.show', $property->slug ?? $property->id) }}" class="hover:underline">
                        {{ $property->title }}
                    </a>
                </h3>
                @if($property->location)
                <p class="text-gray-600">{{ $property->location }}</p>
                @endif
            </div>
            
            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                @if($property->guests)
                <span>{{ $property->guests }} {{ __('guests') }}</span>
                @endif
                @if($property->bedrooms)
                <span>{{ $property->bedrooms }} {{ __('bedrooms') }}</span>
                @endif
                @if($property->rating)
                <div class="flex items-center">
                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                    <span>{{ $property->rating }}</span>
                </div>
                @endif
            </div>
            
            <div class="flex justify-between items-end">
                <div class="text-gray-700 flex-1 mr-4">
                    @if($property->description)
                    {{ Str::limit(strip_tags($property->description), 80) }}
                    @endif
                </div>
                <div class="text-right">
                    @if($property->price_per_night)
                    <div class="text-xl font-light">€{{ number_format($property->price_per_night) }}</div>
                    <div class="text-gray-600 text-sm">{{ __('per night') }}</div>
                    @else
                    <div class="text-sm font-light text-gray-500">{{ __('Price on request') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif