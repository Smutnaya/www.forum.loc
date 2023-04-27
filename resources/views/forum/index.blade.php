@php
    use App\AppForum\Helpers\ForumHelper;
@endphp
@extends('layouts.forum')
@section('title-block')
    @if (!is_null($model['forumTitle']))
        {{ $model['forumTitle'] }} - Форум игры Времена Смуты
    @else
        Форум игры Времена Смуты
    @endif
@endsection
@section('content')
    {{-- @dd($model) --}}
    <div class="container-fluid px-0">
        @if (!is_null($model['breadcrump']) && $model['visForum'])
            @include('inc.breadcrump', ['posts' => $model['breadcrump']])

            @include('section.inc.accordion')
            @if ($errors->has('message'))
                <div class="alert alert-success mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ $errors->first('message') }}</div>
            @endif
            <div class="row mb-2">
                <div class="pb-1 col-8" id="title">
                    <h4 class="title-shadow">{{ $model['forumTitle'] }}</h4>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="col">
                        @if (!is_null($model['pagination']['forumId']) && $model['pagination']['pages'] > 0)
                            @include('forum.inc.pagination', ['model' => $model['pagination']])
                        @endif
                    </div>
                    @if (!is_null($model['user']))
                        @if ($model['sectionId'] != 6)
                            @if (!$model['forumBlock'] && !$model['userBan'] && $model['sectionId'] != 6)
                                <div class="col d-grid gap-2 d-inline-flex justify-content-end" id="title">
                                    <a class="btn btn-sm btn-custom shadow px-0" href="{{ url($model['forumId'] . '/topic') }}">Новая
                                        тема</a>
                                </div>
                            @elseif ($model['moder'])
                                <div class="col d-grid gap-2 d-inline-flex justify-content-end" id="title">
                                    <a class="btn btn-sm btn-custom shadow px-0" href="{{ url($model['forumId'] . '/topic') }}">Новая
                                        тема</a>
                                </div>
                            @elseif ($model['editor'])
                                <div class="col d-grid gap-2 d-inline-flex justify-content-end" id="title">
                                    <a class="btn btn-sm btn-custom shadow px-0" href="{{ url($model['forumId'] . '/topic') }}">Новая
                                        тема</a>
                                </div>
                            @elseif ($model['user_alliance_moder'] || $model['user_clan_moder'])
                                <div class="col d-grid gap-2 d-inline-flex justify-content-end" id="title">
                                    <a class="btn btn-sm btn-custom shadow px-0" href="{{ url($model['forumId'] . '/topic') }}">Новая
                                        тема</a>
                                </div>
                            @endif
                        @elseif ($model['sectionId'] == 6)
                            @if ($model['editor'] || $model['user']['role_id'] > 11)
                                <div class="col d-grid gap-2 d-inline-flex justify-content-end" id="title">
                                    <a class="btn btn-sm btn-custom shadow px-0" href="{{ url($model['forumId'] . '/topic') }}">Новая
                                        тема</a>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
            @if ($model['topics']->count() == 0)
                <div class="my-3 mb-5 centre">Темы отсутствуют</div>
            @else
                <div class="border border-ligh shadow-sm">
                    @foreach ($model['topics'] as $topic)
                        <div class="table-color text-break">
                            <div class="row mx-1 py-1 @if (!is_null($model['user']) && $model['user']['role_id'] < 6  || !$model['moder'] || !$model['visForum']) align-items-center @endif ">
                                <div class="col align-self-center">
                                    @if ($topic['pin'])
                                        <i class="fa fa-thumb-tack forum-desc me-1" title="Закрепеленная тема"></i>
                                    @endif
                                    @if ($topic['block'])
                                        <i class="fa-solid fa-lock forum-desc me-1" title="Тема закрыта"></i>
                                    @endif
                                    @if ($topic['hide'])
                                        <i class="fa-regular fa-eye-slash forum-desc me-1" title="Скрытая тема"></i>
                                    @endif
                                    @if ($topic['moderation'])
                                        <i class="fa-regular fa-hourglass forum-desc me-1" title="Премодерация публикаций в теме"></i>
                                    @endif
                                    @if ($topic['private'] && $model['sectionId'] == 5)
                                        <i class="fa-solid fa-chess-rook forum-desc me-1" title="Внутренний доступ"></i>
                                    @endif
                                    <span class="fw-bold">
                                        <a href="{{ url('/t/' . $topic['id'] . '-' . $topic['title_slug']) }}">{{ $topic['title'] }}
                                        </a>
                                    </span>
                                    <div class="forum-desc">
                                        @if ($model['forumId'] == 53 || $model['sectionId'] != 6)
                                            <a class="post-a-color" href="{{ url('/user/' . $topic['user_id']) }}">{{ $topic['user'] }}</a> &bull; {{ $topic['datetime'] }}
                                        @else
                                            @if ($topic['news_id'] > 0)
                                                # {{ $topic['news_title'] }}
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xl-2 col-lg-3 col-sm-2 d-none d-sm-block align-self-center">
                                    <div class="container-fluid forum-desc">
                                        @if ($model['forumId'] == 53 || $model['sectionId'] != 6)
                                            <div class="row">
                                                <div class="col d-flex justify-content-end">
                                                    Ответы: &nbsp; <span class="fw-bold">{{ $topic['DATA']->inf->post_count }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col d-flex justify-content-end">
                                                    Комментарии: &nbsp; <span class="fw-bold">{{ $topic['DATA']->inf->comment }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row" style="height: 19px !important;">
                                            <div class="col d-flex justify-content-end">
                                                Просмотры: &nbsp; <span class="fw-bold">
                                                    {{ $topic['DATA']->inf->views }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-12">
                                    <hr class="d-xl-none d-block my-1 hr-color">

                                    @if (!is_null($topic['DATA']->last_post->user_name) && !is_null($topic['DATA']->last_post->user_id) && !is_null($topic['DATA']->last_post->date))
                                        @if ($model['sectionId'] != 3)
                                            <div class="row">
                                                <div class="col-2 d-none d-xl-block p-1 align-self-center">
                                                    @if (!is_null($topic['DATA']->last_post->avatar))
                                                        <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="min-avatar rounded" alt="Cinque Terre" src="/storage{{ $topic['DATA']->last_post->avatar }}">
                                                    @else
                                                        <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="min-avatar rounded" alt="Cinque Terre"src="/images/av.png">
                                                    @endif
                                                </div>
                                                <div class="col-2 d-xl-none d-block align-self-center">
                                                    <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="min-avatar-post rounded " alt="Cinque Terre" @if (!is_null($topic['DATA']->last_post->avatar)) src="/storage{{ $topic['DATA']->last_post->avatar }}"
                                                @else
                                                src="/images/av.png" @endif>
                                                </div>
                                                <div class="col-10 align-self-center  px-1" style="font-size: 10pt;">
                                                    <div class="row m-0">
                                                        <div class="col px-2">
                                                            <a class="post-a-color" href="{{ url('/user/' . $topic['DATA']->last_post->user_id) }}"> {{ $topic['DATA']->last_post->user_name }} </a>
                                                        </div>
                                                    </div>
                                                    <div class="row m-0">
                                                        <div class="col px-2">
                                                            <a href="{{ url('/t/' . $topic['id'] . '-' . $topic['title_slug'] . '/end') }}"><span class="forum-desc" style="font-size: 8pt;">
                                                                    {{ ForumHelper::timeFormat($topic['DATA']->last_post->date) }}</span></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($model['sectionId'] == 3)
                                            <div class="row">
                                                @if (!is_null($model['user']) && $model['user']['role_id'] > 5 || $model['moder'] || $model['visForum'])
                                                    <div class="col-2 d-none d-xl-block p-1 align-self-center">
                                                        @if (!is_null($topic['DATA']->last_post->avatar))
                                                            <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="min-avatar rounded" alt="Cinque Terre" src="/storage{{ $topic['DATA']->last_post->avatar }}">
                                                        @else
                                                            <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="min-avatar rounded" alt="Cinque Terre"src="/images/av.png">
                                                        @endif
                                                    </div>
                                                    <div class="col-2 d-xl-none d-block align-self-center">
                                                        <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="min-avatar-post rounded " alt="Cinque Terre" @if (!is_null($topic['DATA']->last_post->avatar)) src="/storage{{ $topic['DATA']->last_post->avatar }}"
                                                @else
                                                src="/images/av.png" @endif>
                                                    </div>
                                                @endif
                                                <div class="col-10 align-self-center px-1" style="font-size: 10pt;">
                                                    @if (!is_null($model['user']) && $model['user']['role_id'] > 5 || $model['moder'] || $model['visForum'])
                                                        <div class="row m-0">
                                                            <div class="col px-2">
                                                                <a class="post-a-color" href="{{ url('/user/' . $topic['DATA']->last_post->user_id) }}"> {{ $topic['DATA']->last_post->user_name }} </a>
                                                            </div>
                                                        </div>
                                                        <div class="row m-0">
                                                            <div class="col px-2">
                                                                <a href="{{ url('/t/' . $topic['id'] . '-' . $topic['title_slug'] . '/end') }}"><span class="forum-desc" style="font-size: 8pt;">
                                                                        {{ ForumHelper::timeFormat($topic['DATA']->last_post->date) }}</span></a>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <div class="col px-2">
                                                                <a href="{{ url('/t/' . $topic['id'] . '-' . $topic['title_slug'] . '/end') }}"><span class="forum-desc" style="font-size: 8pt;">
                                                                        {{ ForumHelper::timeFormat($topic['DATA']->last_post->date) }}</span></a>
                                                            </div>
                                                        @endif
                                                </div>
                                            </div>
                                        @endif


                                        {{-- @else
                                    <div class="col forum-desc d-flex justify-content-center align-items-center text-break">
                                        Ответов не найдено </div> --}}
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="col mt-2">
                    @include('forum.inc.pagination', ['model' => $model['pagination']])
                </div>
            @endif
        @else
            <div class="my-3 mb-5 centre error" style="color:red">Форум с темами не найден</div>
        @endif
    </div>
@endsection
