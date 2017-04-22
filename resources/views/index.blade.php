@extends('app.layout')

@section('content')
    @parent

    <!-- begin hero -->
    @include('app.sections.hero')
    <!-- end hero -->

    @include('app.sections.couple')
    @include('app.sections.storyboard')
    @include('app.sections.event')
    @include('app.sections.attending')
    @include('app.sections.modals')
@endsection
