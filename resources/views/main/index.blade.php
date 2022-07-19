@extends('layouts.main')

@section('content')
    <div class="container px-0">
        <div class="row">
            <div class="col-lg-10 col-md-12">
                <div class="row">@include('main.inc.news')</div>
                <div class="row mt-4">@include('main.inc.online')</div>
            </div>
            <div class="col-lg-2 col-md-12">
                @include('main.inc.topic_info')
            </div>
        </div>
    </div>
@endsection
