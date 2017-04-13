@extends('app.layout')

@section('content')
    @parent

    <!-- begin hero -->
    @include('app.sections.hero')
    <!-- end hero -->

    @include('app.sections.couple')
    @include('app.sections.storyboard')
@endsection
