@extends('layouts.base')

@section('title', $title)
@section('meta_description', 'Risultati della ricerca per ' . $title)

@section('content')


@foreach($properties as $property)
    <div class="article">
        <h2>{{ $property->title }}</h2>
        <p>{{ $property->excerpt }}</p>
        <a href="{{ $property->url }}">Leggi di più</a>
    </div>
@endforeach

@endsection