@extends('layouts.topic')
@section('title-block')
    @if (!is_null($model['user']))
        Обработка жалоб - Форум игры Времена Смуты
    @else
        Форум игры Времена Смуты
    @endif
@endsection
@section('content')
    <div class="conteiner-fluid">
        @if ($errors->has('message'))
            <div class="alert alert-success mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ $errors->first('message') }}</div>
        @endif
        @if (Session::has('messageOk'))
            <div class="mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #009b7421 !important; border-color: #4f5c541c !important; border: 1px solid transparent;
        border-radius: 0.25rem; text-align: center;">{{ Session::get('messageOk') }}</div>
        @endif
        <div class="row mb-2">
            <div class="pb-1 col-12" id="title">
                <h4 class="title-shadow">Обработка жалоб</h4>
            </div>
        </div>

        @if ($model['complaints']->count() == 0)
            <div class="my-3 mb-5 centre">Нет жалоб ожидающих рассмотрения</div>
        @else
            @foreach ($model['complaints'] as $complaint)
                <div class="my-2">
                    <div class="forum-desc">{{ $complaint['datetime'] }} &bull; от <a class="post-a-color" href="{{ url('/user/' . $complaint['user_id']) }}"> {{ $complaint['user_name'] }}</a></div>
                    <div>
                        <a href="{{ url('/f/' . $complaint['forum_id']) }}"> {{ $complaint['forum_title'] }}</a>
                        / <span class="fw-bold"><a href="{{ url('/t/' . $complaint['topic_id']) }}"> {{ $complaint['topic_title'] }}</a> </span>
                        / <span class="text-secondary"> #{{ $complaint['post_id'] }} - <span class="forum-desc">{{ $complaint['post_datetime'] }} &bull; <a href="{{ url('/user/' . $complaint['post_user_id']) }}"> {{ $complaint['post_user_name'] }}</a></span></span>
                    </div>
                    <div>
                        <div class="d-inline-block">
                            <form method='post' action='{{ url('/cw/' . $complaint['id'] . '/ok') }}'>
                                @csrf
                                <button type="submit" class="btn ps-0 py-0 fw-bold" style="background-color: transparent !important; border: none !important; box-shadow: none !important;">
                                    <span style="color: #006843 !important;">Подтвердить</span>
                                </button>
                            </form>
                        </div>
                        <div class="d-inline-block">
                            <form method='post' action='{{ url('/cw/' . $complaint['id'] . '/no') }}'>
                                @csrf
                                <button type="submit" class="btn ps-0 py-0 fw-bold" style="background-color: transparent !important; border: none !important; box-shadow: none !important;">
                                    <span style="color: #6a0000 !important;">Отклонить</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
{{-- <div class="col-1 d-flex justify-content-end align-items-center">
                                <form method='post' action='{{ url('/' . $message['id'] . '/hide') }}'>
                                    @csrf
                                    <input type="submit" class="btn-check btn-id" name="check[]" id="{{ $message['id'] }}" value="{{ $message['id'] }}">
                                    <label for="{{ $message['id'] }}">
                                        <i type="button" class="fa-solid fa-xmark"></i>
                                    </label>
                                </form>
                            </div> --}}
