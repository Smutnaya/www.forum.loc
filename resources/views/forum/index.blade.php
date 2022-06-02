
@extends('layouts.forum')
@section('content')
<div class="container px-0">
    <div class="pb-1 align-self-center" id="breadcrump">

        <?php
        echo htmlspecialchars_decode($model['breadcrump']);
        ?>
        <hr class="d-block d-lg-none hr-color">
    </div>
<div class="row mb-1">
    <div class="pb-1 col-8" id="title">
        <h4>{{ $model['forumTitle'] }}</h4>
    </div>
    <div class="pb-1 col-4 d-flex justify-content-end " id="title">
        <a class="btn btn-sm btn-custom" href="{{ url('/f/'.$forumId.'/topic')}}">Новая тема</a>
    </div>
</div>

@if($model['topics']->count() == 0)
    <div class="my-3 mb-5 centre">Темы отсутствуют</div>
@endif
<div class="border border-ligh">
    @foreach ($model['topics'] as $topic)
        <div class="table-color">
            <div class="row ms-1">
                <div class="col-xl-6 col-lg-9 col-sm-10 col-12">
                    <div><a href="{{ url('/t/' . $topic['id']) }}">{{ $topic['title'] }}
                        </a>
                    </div>
                    <div class="forum-desc-user">
                        <span class="text-muted"><a class="post-a-color" href="{{ url('#') }}">Шустрая Улитка</a> &bull; Сегодня в 08:00</span>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-3 col-sm-2  d-none d-sm-block m-auto">
                    <div class="container-fluid m-auto forum-desc">
                        <div class="row">
                            <div class="centre col-12">
                                просмотров
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
                            <img class="min-avatar" alt="Cinque Terre" src="https://avatarko.ru/img/avatar/12/zhivotnye_ptica_sova_11535.jpg"></div>
                        <div class=" col-10">
                        <a class="post-a-color"href="{{ url('#') }}">Времена Смуты - об игре</a>
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


