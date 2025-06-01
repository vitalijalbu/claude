@extends('layouts.base')

@section('title', 'Home')

@section('meta_description', 'description')

@section('content')

@include('shared.sections.hero')

@include('shared.sections.destinations')

@include('shared.sections.properties-grid')

@include('shared.sections.testimonials')

@endsection