@extends('layouts.main')
@section('title-block')Форум игры Времена Смуты@endsection
@section('content')
    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-xl-9 col-lg-8 col-md-12 scroll">
                <div class="row">@include('main.inc.news')</div>
                <div class="row mt-4">@include('main.inc.online')</div>
            </div>
            <div class="col-xl-3 col-lg-4 d-none d-lg-block">
                @include('inc.upTopic', ['model' => $model])
            </div>
            {{-- <div class="col-12 d-lg-none d-md-block mt-4 px-0">
                @include('inc.upTopic', ['model' => $model])
            </div> --}}
        </div>
    </div>
@endsection
