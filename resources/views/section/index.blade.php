@extends('layouts.forum')
@section('content')
<div class="container px-0">

    @include('inc.breadcrump', ['posts' => $model['breadcrump']])

    @include('section.inc.accordion')

    <hr class="hr-color d-md-none d-sm-block ">
    <div class="pb-1" id="title">
        <h4 class="title-shadow">{{ $model['sectionTitle'] }}</h4>
    </div>

    <div class="border border-ligh shadow-sm">
        @foreach ($model['forums'] as $forum)
            <div class="table-color">
                <div class="row ms-1">
                    <div class="col-xl-7 col-lg-9 col-sm-10 col-12">
                        <div class="fw-bold"><a class="post-a-color" href="{{ url('/f/' . $forum['id']) }}">{{ $forum['title'] }} </a></div>
                        <div class="forum-desc">{{ $forum['description'] }}</div>
                    </div>

                    <div class="col-xl-1 col-lg-3 col-sm-2  d-none d-sm-block m-auto">
                        <div class="container-fluid m-auto forum-desc">
                            <div class="row">
                                <div class="centre col-12">
                                    Тем
                                </div>
                                <div class="centre col-12">
                                    ответов
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-12 col-12 align-self-center px-auto">
                        <hr class="d-xl-none d-block my-1 hr-color">
                        <div class="row">
                            <div class="col-2">
                                <img style="border-color: #ced4da;" class="min-avatar border bg-white rounded" alt="Cinque Terre" src="https://avatarko.ru/img/avatar/12/zhivotnye_ptica_sova_11535.jpg"></div>
                            <div class=" col-10">
                            <a class="post-a-color" href="{{ url('#') }}">Времена Смуты - об игре</a>
                            <br>
                            <a class="text-dark" href="{{ url('#') }}">Volk</a> <span class="text-muted">&bull;&nbsp;Сегодня в 10:00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
