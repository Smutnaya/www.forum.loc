@extends('layouts.forum')
@section('content')
    <div class="container px-0">
        <div class="pb-1 align-self-center" id="breadcrump">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href={{ url('/main') }}>Главная</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Форумы</li>
                </ol>
            </nav>
            <hr class="d-block d-lg-none hr-color">
        </div>

        @foreach ($model['sections'] as $section)
            <h4 class="title-shadow">{{ $section['title'] }}</h4> <i class="bi bi-eye-slash"></i>

            <div class="border border-ligh shadow-sm">

                @foreach ($section['forums'] as $forum_s)
                    @foreach ($model['forums'] as $forum)
                        @if ($forum['id'] == $forum_s['id'])
                            <div class="table-color ">
                                <div class="row mx-1 py-1">
                                    <div class="col">
                                        <div class="fw-bold"><a class="post-a-color"
                                                href="{{ url('/f/' . $forum['id']) }}">{{ $forum['title'] }} </a></div>
                                        <div class="forum-desc">{{ $forum['description'] }}</div>
                                    </div>

                                    <div class="col-xl-1 col-lg-3 col-sm-3 d-none d-sm-block align-self-center"
                                        style="min-width: 90px;">
                                        <div class="container-fluid forum-desc">
                                            <div class="row">
                                                <div class="col d-flex justify-content-end align-items-center text-center ">
                                                    Темы: {{ $forum['DATA']->inf->topic_count }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col d-flex justify-content-end align-items-center text-center ">
                                                    Ответы: {{ $forum['DATA']->inf->post_count }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-12 align-self-center px-auto" style="min-width: 250px;">

                                        <hr class="d-xl-none d-block my-1 hr-color">
                                        @if (!is_null($forum['DATA']->last_post->user_name) && !is_null($forum['DATA']->last_post->user_id) && !is_null($forum['DATA']->last_post->date) && !is_null($forum['DATA']->last_post->title) && !is_null($forum['DATA']->last_post->post_id))
                                            <div class="row">
                                                <div class="col-2 d-none d-xl-block p-1">
                                                    <img style="border-color: #ced4da;"
                                                        class="min-avatar border bg-white rounded" alt="Cinque Terre"
                                                        src="https://avatarko.ru/img/avatar/12/zhivotnye_ptica_sova_11535.jpg">
                                                </div>
                                                <div class="col-1 d-xl-none d-block  p-1">
                                                    <img style="border-color: #ced4da;"
                                                        class="min-avatar-post border bg-white rounded" alt="Cinque Terre"
                                                        src="https://avatarko.ru/img/avatar/12/zhivotnye_ptica_sova_11535.jpg">
                                                </div>
                                                <div class="col-10 align-self-center">
                                                    <div class="row">
                                                        <div class="col d-flex justify-content-start ps-2 pe-0">
                                                            <a class="post-a-color" style="font-size: 10pt !important;"
                                                                href="{{ url('/t/' . $forum['DATA']->last_post->post_id) }} ">
                                                                {{ $forum['DATA']->last_post->title }} </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div
                                                            class="col d-flex justify-content-start align-items-start ps-2 pe-0">
                                                            <span class="forum-desc">
                                                                <a class="text-dark" style="font-size: 10pt;"
                                                                    href="{{ url('#') }}">{{ $forum['DATA']->last_post->user_name }}</a>
                                                                <span style="font-size: 8pt;"
                                                                    class="text-muted">&bull;&nbsp;{{ date('d.m.Y в H:i', $forum['DATA']->last_post->date) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- @else
                        <div class="col forum-desc d-flex justify-content-center align-items-center text-break">
                            Ответов не найдено </div> --}}
                                        @endif
                                    </div>
                                </div>
                            </div>{{--  --}}
                        @endif
                    @endforeach
                @endforeach

            </div>
            <br>
        @endforeach
    </div>
@endsection
