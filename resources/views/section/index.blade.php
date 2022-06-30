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
                <div class="table-color ">
                    <div class="row mx-1 py-1">
                        <div class="col">
                            <div class="fw-bold"><a class="post-a-color"
                                    href="{{ url('/f/' . $forum['id']) }}">{{ $forum['title'] }} </a></div>
                            <div class="forum-desc">{{ $forum['description'] }}</div>
                        </div>

                        <div class="col-xl-1 col-lg-3 col-sm-3 d-none d-sm-block align-self-center" style="min-width: 90px;">
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
                                        <img style="border-color: #ced4da;" class="min-avatar border bg-white rounded"
                                            alt="Cinque Terre"
                                            src="https://avatarko.ru/img/avatar/12/zhivotnye_ptica_sova_11535.jpg">
                                    </div>
                                    <div class="col-1 d-xl-none d-block  p-1">
                                        <img style="border-color: #ced4da;" class="min-avatar-post border bg-white rounded"
                                            alt="Cinque Terre"
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
                                            <div class="col d-flex justify-content-start align-items-start ps-2 pe-0"><span
                                                    class="forum-desc">
                                                    <a class="text-dark" style="font-size: 10pt;"
                                                        href="{{ url('#') }}">{{ $forum['DATA']->last_post->user_name }}</a>
                                                    <span style="font-size: 8pt;"
                                                        class="text-muted">&bull;&nbsp;{{ date('d.m.Y в H:i', $forum['DATA']->last_post->date) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                         {{--    @else
                                <div class="col forum-desc d-flex justify-content-center align-items-center text-break">
                                    Ответов не найдено </div> --}}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
