@extends('layouts.base')

@section('title', __('Risultati della ricerca per :city', ['city' => $data->name]))

@section('content')
    <div class="container">
        <div class="py-6">
            <x-breadcrumbs :links="[
                ['url' => route('home'), 'name' => __('Home')],
                ['url' => route('city.show', $data->slug), 'name' => $data->name],
            ]" />
            <x-search.filters :data="$data" />
            <search-filters />
            <x-page-header :title="__('Risultati della ricerca per :city', ['city' => $data->name])" />
            <div class="relative flex flex-col space-y-6">
                @if ($listings->isNotEmpty())
                    <div class="flex flex-col space-y-4">
                        @foreach ($listings as $listing)
                            <x-listings.item :data="$listing" />
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $listings->links() }}
                    </div>
                @else
                    <p class="text-gray-500">{{ __('site.no_data') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
