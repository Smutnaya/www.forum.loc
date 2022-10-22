@extends('layouts.main')
@section('title-block')
    Форум игры Времена Смуты
@endsection
@section('content')
    <div class="container-fluid p-0">
        @include('section.inc.accordion')
        <div class="row m-0">
            <div class="col-xl-9 col-lg-8 col-md-12 scroll">
                <div class="row">@include('main.inc.news')</div>
                <div class="row p-0 mt-4 d-none d-lg-block">@include('main.inc.online')</div>
            </div>
            <div class="col-xl-3 col-lg-4 d-none d-lg-block">
                @include('inc.upTopic', ['model' => $model])
            </div>
        </div>
        <div class="row m-0 my-3">
            <div class="col-xl-12 d-block d-lg-none p-0">
                @include('inc.upTopic', ['model' => $model])
            </div>
        </div>
        <div class="row m-0">
            <div class="col-xl-12 d-block d-lg-none p-0">
                @include('main.inc.online')
            </div>
        </div>

    </div>
@endsection
