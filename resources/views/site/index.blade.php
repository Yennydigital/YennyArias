@extends('layouts.site.basic')

@section('content')
    @include('site.partials.hero')
    @include('site.partials.about')
    @include('site.partials.stack')
    @include('site.partials.projects')
    @include('site.partials.contact')
    <livewire:chat-widget />
@endsection
