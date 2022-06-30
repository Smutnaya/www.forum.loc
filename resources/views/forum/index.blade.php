@extends('layouts.forum')
@section('content')
    <div class="container px-0">


        @if (!is_null($model['breadcrump']))
            @include('inc.breadcrump', ['posts' => $model['breadcrump']])
        @endif
        @if ($errors->has('message'))
            <div class="error" style="color:red">{{ $errors->first('message') }}</div>
        @endif
        <div class="row mb-1">
            <div class="pb-1 col-8" id="title">
                <h4 class="title-shadow">{{ $model['forumTitle'] }}</h4>
            </div>
            <div class="pb-1 col-4 d-flex justify-content-end " id="title">
                <a class="btn btn-sm btn-custom shadow" href="{{ url('/f/' . $forumId . '/topic') }}">Новая тема</a>
            </div>
        </div>

        @if ($model['topics']->count() == 0)
            <div class="my-3 mb-5 centre">Темы отсутствуют</div>
        @endif

        <div class="border border-ligh shadow-sm">
            @foreach ($model['topics'] as $topic)
                <div class="table-color">
                    <div class="row mx-1 pt-1">
                        <div class="col text-break">
                            @if ($topic['pin'])
                                <i class="fa fa-thumb-tack forum-desc" title="Закрепеленная тема"></i>
                            @endif
                            @if ($topic['block'])
                                <i class="fa-solid fa-lock forum-desc" title="Тема закрыта"></i>
                            @endif
                            @if ($topic['hide'])
                                <i class="fa-regular fa-eye-slash forum-desc" title="Скрытая тема"></i>
                            @endif
                            @if ($topic['moderation'])
                                <i class="fa-regular fa-hourglass forum-desc me-1"
                                    title="Премодерация публикаций в теме"></i>
                            @endif

                            <a href="{{ url('/t/' . $topic['id']) }}">{{ $topic['title'] }} </a>

                            <div class="forum-desc-user">
                                <span class="text-muted"><a class="post-a-color" href="{{ url('#') }}">
                                        {{ $topic['user'] }}
                                    </a> &bull; {{ date('d.m.Y в H:i', $topic['datatime']) }}</span>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-sm-2 d-none d-sm-block m-auto align-self-center">
                            <div class="container-fluid m-auto forum-desc">
                                <div class="row">
                                    <div class="col d-flex justify-content-end align-items-center text-center ">
                                        Просмотры: {{ $topic['DATA']->inf->post_count }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col d-flex justify-content-end align-items-center text-center ">
                                        Ответы: {{ $topic['DATA']->inf->post_count }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-12 align-self-center px-auto">
                            <hr class="d-xl-none d-block my-1 hr-color">
                            <div class="row">
                                @if (!is_null($topic['DATA']->last_post->user_name) && !is_null($topic['DATA']->last_post->user_id) && !is_null($topic['DATA']->last_post->date))
                                    <div class="col-2 d-none d-xl-block">
                                        <img style="border-color: #ced4da;" class="min-avatar border bg-white rounded"
                                            alt="Cinque Terre"
                                            src="https://avatarko.ru/img/avatar/12/zhivotnye_ptica_sova_11535.jpg">
                                    </div>
                                    <div class="col-1 d-xl-none d-block">
                                        <img style="border-color: #ced4da;" class="min-avatar-post border bg-white rounded"
                                            alt="Cinque Terre"
                                            src="https://avatarko.ru/img/avatar/12/zhivotnye_ptica_sova_11535.jpg">
                                    </div>
                                    <div class="col-10" style="font-size: 10pt;">
                                        <div class="row ps-3">
                                            <div class="col d-flex justify-content-start">
                                                {{ $topic['DATA']->last_post->user_name }} </a>
                                            </div>
                                        </div>
                                        <div class="row ps-3">
                                            <div class="col d-flex justify-content-start align-items-start"><span
                                                    class="forum-desc"  style="font-size: 8pt;">{{ date('d.m.Y в H:i', $topic['DATA']->last_post->date) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                {{-- @else
                                    <div class="col forum-desc d-flex justify-content-center align-items-center text-break">
                                        Ответов не найдено </div> --}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
