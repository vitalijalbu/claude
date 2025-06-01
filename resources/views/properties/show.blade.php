@extends('layouts.base')

@section('title', $title)
@section('meta_description', 'description')

@section('content')


{{ $page->id }}
{{ $page->title }}
{{ $page->content }}
{{ $page->slug }}




@endsection