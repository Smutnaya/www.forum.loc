@php
use App\AppForum\Helpers\ForumHelper;
@endphp
@extends('layouts.forum')
@section('content')

    <div class="container px-0">
        @if (!is_null($model['breadcrump']) && $model['visForum'])
            @include('inc.breadcrump', ['posts' => $model['breadcrump']])


            @if ($errors->has('message'))
                <div class="error" style="color:red">{{ $errors->first('message') }}</div>
            @endif
            <div class="row mb-2">
                <div class="pb-1 col-8" id="title">
                    <h4 class="title-shadow">{{ $model['forumTitle'] }}</h4>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="col">
                        @if (!is_null($model['pagination']['forumId']))
                            @include('forum.inc.pagination', ['model' => $model['pagination']])
                        @endif
                    </div>
                    @if (!is_null($model['user']))
                        @if (!$model['forumBlock'] && !$model['userBan'])
                            <div class="col d-grid gap-2 d-inline-flex justify-content-end" id="title">
                                <a class="btn btn-sm btn-custom shadow" href="{{ url($model['forumId'] . '/topic') }}">Новая
                                    тема</a>
                            </div>
                        @elseif ($model['newPost'])
                            <div class="col d-grid gap-2 d-inline-flex justify-content-end" id="title">
                                <a class="btn btn-sm btn-custom shadow" href="{{ url($model['forumId'] . '/topic') }}">Новая
                                    тема</a>
                            </div>
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
                            <div class="row mx-1 py-1 ">
                                <div class="col align-self-center">
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
                                        <i class="fa-regular fa-hourglass forum-desc me-1" title="Премодерация публикаций в теме"></i>
                                    @endif
                                    <span class="fw-bold">
                                        <a href="{{ url('/t/' . $topic['id'] . '-' . $topic['title_slug']) }}">{{ $topic['title'] }}
                                        </a>
                                    </span>
                                    <div class="forum-desc">
                                        <a class="post-a-color" href="{{ url('/user/' . $topic['user_id']) }}">{{ $topic['user'] }}</a> &bull; {{ $topic['datetime'] }}
                                    </div>
                                </div>

                                <div class="col-xl-2 col-lg-3 col-sm-2 d-none d-sm-block align-self-center">
                                    <div class="container-fluid forum-desc">
                                        <div class="row">
                                            <div class="col d-flex justify-content-end">
                                                Просмотры: &nbsp; <span class="fw-bold">
                                                    {{ $topic['DATA']->inf->views }}</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col d-flex justify-content-end">
                                                Ответы: &nbsp; <span class="fw-bold">{{ $topic['DATA']->inf->post_count }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-12 ">
                                    <hr class="d-xl-none d-block my-1 hr-color">

                                    @if (!is_null($topic['DATA']->last_post->user_name) && !is_null($topic['DATA']->last_post->user_id) && !is_null($topic['DATA']->last_post->date))
                                        <div class="row">
                                            <div class="col-2 d-none d-xl-block p-1 align-self-center">
                                                <img style="border-color: #ced4da;" class="min-avatar border bg-white rounded" alt="Cinque Terre" src="https://avatarko.ru/img/avatar/12/zhivotnye_ptica_sova_11535.jpg">
                                            </div>
                                            <div class="col-2 d-xl-none d-block align-self-center">
                                                <img style="border-color: #ced4da;" class="min-avatar-post border bg-white rounded " alt="Cinque Terre" src="https://avatarko.ru/img/avatar/12/zhivotnye_ptica_sova_11535.jpg">
                                            </div>
                                            <div class="col-10 align-self-center" style="font-size: 10pt;">
                                                <div class="row ps-3">
                                                    <div class="col">
                                                        <a class="post-a-color" href="{{ url('/user/' . $topic['DATA']->last_post->user_id) }}"> {{ $topic['DATA']->last_post->user_name }} </a>
                                                    </div>
                                                </div>
                                                <div class="row ps-3">
                                                    <div class="col">
                                                        <a href="{{ url('/t/' . $topic['id'] . '-' . $topic['title_slug'] . '/end') }}"><span class="forum-desc" style="font-size: 8pt;">
                                                                {{ ForumHelper::timeFormat($topic['DATA']->last_post->date) }}</span></a>
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
