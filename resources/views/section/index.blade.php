@php
    use App\AppForum\Helpers\ForumHelper;
@endphp
@extends('layouts.forum')
@section('title-block')
    @if (!is_null($model['sectionTitle']))
        {{ $model['sectionTitle'] }} - Форум игры Времена Смуты
    @else
        Форум игры Времена Смуты
    @endif
@endsection
@section('content')
    {{-- @dd($model) --}}
    <div class="container-fluid px-0">
        @if ($model['forums']->count() == 0)
            <div class="my-3 mb-5 centre error" style="color:red">Раздел с форумами не найден</div>
        @else
            @include('inc.breadcrump', ['posts' => $model['breadcrump']])

            @include('section.inc.accordion')
            <div class="pb-1" id="title">
                <h4 class="title-shadow">{{ $model['sectionTitle'] }}</h4>
            </div>

            <div class="border border-ligh shadow-sm ">
                @foreach ($model['forums'] as $forum)
                    <div class="table-color text-break">
                        <div class="row mx-1 py-1">
                            <div class="col align-self-center">
                                <div class="fw-bold"><a class="post-a-color" href="{{ url('/f/' . $forum['id']) }}">{{ $forum['title'] }} </a></div>
                                <div class="forum-desc">{{ $forum['description'] }}</div>
                            </div>

                            <div class="col-xl-1 col-lg-3 col-sm-3 d-none d-sm-block align-self-center" style="min-width: 90px;">
                                <div class="container-fluid forum-desc">
                                    <div class="row">
                                        <div class="col d-flex justify-content-end">
                                            Темы: &nbsp; <span class="fw-bold">{{ $forum['DATA']->inf->topic_count }}</span>
                                        </div>
                                    </div>
                                    @if ($forum['id'] == 53 || $forum['section_id'] != 6)
                                        <div class="row">
                                            <div class="col d-flex justify-content-end">
                                                Ответы: &nbsp; <span class="fw-bold">{{ $forum['DATA']->inf->post_count }}</span>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-12 align-self-center" style="min-width: 250px;">

                                <hr class="d-xl-none d-block my-1 hr-color ">
                                @if (!is_null($forum['DATA']->last_post->user_name) && !is_null($forum['DATA']->last_post->user_id) && !is_null($forum['DATA']->last_post->date) && !is_null($forum['DATA']->last_post->title) && !is_null($forum['DATA']->last_post->post_id))
                                    @if ($forum['section_id'] != 3)
                                        <div class="row">
                                            <div class="col-2 d-none d-xl-block p-1 align-self-center">
                                                <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="min-avatar rounded" alt="Cinque Terre" @if (!is_null($forum['DATA']->last_post->avatar)) src="/storage{{ $forum['DATA']->last_post->avatar }}"
                                            @else
                                            src="/images/av.png" @endif>
                                            </div>
                                            <div class="col-2 d-xl-none d-block align-self-center">
                                                <img sstyle="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="min-avatar-post rounded " alt="Cinque Terre" @if (!is_null($forum['DATA']->last_post->avatar)) src="/storage{{ $forum['DATA']->last_post->avatar }}"
                                            @else
                                            src="/images/av.png" @endif>
                                            </div>
                                            <div class="col-10 align-self-center p-0  ps-2 pe-0">
                                                <div class="row m-0">
                                                    <div class="col p-0">
                                                        <a class="post-a-color" style="font-size: 10pt !important;" href="{{ url('/t/' . $forum['DATA']->last_post->post_id) }} ">
                                                            {{ $forum['DATA']->last_post->title }} </a>
                                                    </div>
                                                </div>
                                                <div class="row m-0">
                                                    <div class="col p-0">
                                                        <span class="forum-desc">
                                                            <span>
                                                                <a class="text-dark" style="font-size: 10pt;" href="{{ url('/user/' . $forum['DATA']->last_post->user_id) }}">{{ $forum['DATA']->last_post->user_name }}</a>
                                                            </span>
                                                            <span style="font-size: 8pt;" class="text-muted d-inline-block"> &bull;&nbsp;
                                                                <a href="{{ url('/t/' . $forum['DATA']->last_post->post_id . '/end') }} ">{{ ForumHelper::timeFormat($forum['DATA']->last_post->date) }}
                                                                </a>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($forum['section_id'] == 3)
                                        <div class="row">
                                            @if (!is_null($model['user']) && $model['user']['role_id'] > 5)
                                                <div class="col-2 d-none d-xl-block p-1 align-self-center">
                                                    <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="min-avatar rounded" alt="Cinque Terre" @if (!is_null($forum['DATA']->last_post->avatar)) src="/storage{{ $forum['DATA']->last_post->avatar }}"
                                            @else
                                            src="/images/av.png" @endif>
                                                </div>
                                                <div class="col-2 d-xl-none d-block align-self-center">
                                                    <img sstyle="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="min-avatar-post rounded " alt="Cinque Terre" @if (!is_null($forum['DATA']->last_post->avatar)) src="/storage{{ $forum['DATA']->last_post->avatar }}"
                                            @else
                                            src="/images/av.png" @endif>
                                                </div>
                                            @endif
                                            <div @if (!is_null($model['user']) && $model['user']['role_id'] > 5) class="col-10 align-self-center p-0  ps-2 pe-0" @else class="col-12 align-self-center" @endif>
                                                <div class="row m-0">
                                                    <div class="col p-0">
                                                        <a class="post-a-color" style="font-size: 10pt !important;" href="{{ url('/t/' . $forum['DATA']->last_post->post_id) }} ">
                                                            {{ $forum['DATA']->last_post->title }} </a>
                                                    </div>
                                                </div>
                                                <div class="row m-0">
                                                    <div class="col p-0">
                                                        <span class="forum-desc">
                                                            @if (!is_null($model['user']) && $model['user']['role_id'] > 5)
                                                                <span>
                                                                    <a class="text-dark" style="font-size: 10pt;" href="{{ url('/user/' . $forum['DATA']->last_post->user_id) }}">{{ $forum['DATA']->last_post->user_name }}</a>
                                                                </span>
                                                            @endif
                                                            <span style="font-size: 8pt;" class="text-muted d-inline-block">
                                                                @if (!is_null($model['user']) && $model['user']['role_id'] > 5)
                                                                    &bull;&nbsp;
                                                                @endif
                                                                <a href="{{ url('/t/' . $forum['DATA']->last_post->post_id . '/end') }} ">{{ ForumHelper::timeFormat($forum['DATA']->last_post->date) }}
                                                                </a>
                                                            </span>
                                                    </div>
                                                </div>
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
        @endif
    </div>
@endsection
