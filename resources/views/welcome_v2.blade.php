@extends('layouts.landing')

@section('title', config('app.name', 'Parthaistic Agency') . ' - Creative Landing')

@section('content')
    @include('sections.hero')
    @include('sections.services')
    @include('sections.portfolio')
    @include('sections.process')
    @include('sections.testimonial')
    @include('sections.cta')
@endsection

