@extends('layouts.base')
@section('title', __('site.404'))

@section('content')

    <div class="container max-w-md flex-auto flex-col">
        <div class="relative flex min-h-dvh items-center overflow-hidden">
            <div class="relative container mx-auto space-y-16 px-8 py-16 text-center lg:py-32 xl:max-w-7xl">
                <div>
                    <div class="text-6xl font-extrabold text-primary-600 md:text-7xl dark:text-primary-500">
                        400
                    </div>
                    <h1 class="mb-3 text-2xl font-extrabold md:text-3xl">
                        Oops! Your Request is a Bit Off
                    </h1>
                </div>
                <x-button href="/" icon="chevron-left" iconPosition="left">
                    Back Home
                </x-button>
            </div>
        </div>
    </div>
@endsection
