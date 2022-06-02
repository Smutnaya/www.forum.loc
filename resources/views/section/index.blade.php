@extends('layouts.forum')
@section('content')
<div class="container px-0">

    <div class="pb-1 align-self-center" id="breadcrump">

        <?php
        echo htmlspecialchars_decode($model['breadcrump']);
        ?>
        <hr class="d-block d-lg-none hr-color">
    </div>


    <div class="accordion accordion-flush d-block d-md-none" id="accordionFlushExample">
        <div class="accordion-item" style="background:#f1e9c2; color:#050505;">
          <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed fs-6 pt-0 pb-1" type="button" style="background:#f1e9c2; color:#050505;" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Форумы
            </button>
          </h2>
          <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body fs-6 pt-1 pb-0">
                <ul class="navbar-nav mr-auto sidenav fs-6">
                    <li class="nav-item">
                        <a href="{{ url('/s/1') }}">Времена Смуты</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/s/2') }}">Дополнительные форумы</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/s/3') }}">Сенат</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/s/4') }}">Кланы и альянсы - Политика</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/s/3') }}">Клановые форумы</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/s/3') }}">Сенат. Рабочий</a>
                    </li>
                </ul>
            </div>
          </div>
        </div>
      </div>

    <hr class="hr-color d-md-none d-sm-block ">
    <div class="pb-1" id="title">
        <h4>{{ $model['sectionTitle'] }}</h4>
    </div>

    <div class="border border-ligh">
        @foreach ($model['forums'] as $forum)
            <div class="table-color">
                <div class="row ms-1">
                    <div class="col-xl-7 col-lg-9 col-sm-10 col-12">
                        <div class="fw-bold"><a class="post-a-color" href="{{ url('/f/' . $forum['id']) }}">{{ $forum['title'] }}
                            </a>
                        </div>
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
                                <img class="min-avatar" alt="Cinque Terre" src="https://avatarko.ru/img/avatar/12/zhivotnye_ptica_sova_11535.jpg"></div>
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

{{-- <div class="centre col-md-">
    Тем
</div>
<div class="centre col-md-3">
    Постов
</div>
<div class="col-md-6 centre">
    Последнее сообщение
</div> --}}
