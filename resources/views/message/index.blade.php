@extends('layouts.topic')
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
                <h4 class="title-shadow">Сообщения</h4>
            </div>
            <div class="d-flex justify-content-between">
                <div class="col">
                    @if ($model['messages']->count() > 0)
                        @include('message.inc.pagination', ['model' => $model['pagination']])
                    @endif
                </div>
                @if (!is_null($model['user']))
                    @if (!$model['user']['ban_message'])
                        <div class="col d-grid gap-2 d-inline-flex justify-content-end" id="title">
                            <a class="btn btn-sm btn-custom shadow" href="{{ url('/new_message') }}" style="width: 130px !important;">Новое сообщение</a>
                        </div>
                    @else
                        <span class="centre p-2" style="color: #6a0000">Отправка сообщений не доступна, обратитесь к Администрации</span>
                    @endif
                @endif
            </div>
        </div>

        @if ($model['messages']->count() == 0)
            <div class="my-3 mb-5 centre">Сообщения отсутствуют</div>
        @else
            <div class="border border-ligh shadow-sm">
                @foreach ($model['messages'] as $message)
                    <div class="table-color text-break">
                        <div class="row mx-1 py-1 ">
                            <div class="col align-self-center text-break">
                                <div class="col">
                                    @if ($message['user']['id'] != $model['user']['id'] && !$message['view'])
                                        <span style="color: #0e7a32 !important; font-size: 15px; margin-left: -10px;" class="font-bold flicker align-items-center">&bull;</span>
                                    @endif
                                    Тема: <span class="fw-bold">
                                        <a href="{{ url('/m/' . $message['id']) }}">{{ $message['title'] }}
                                        </a>
                                    </span>

                                    @if ($message['user']['id'] != $model['user']['id'] && !$message['view'])
                                        {{-- <span style="color: #0e7a32 !important; font-size: 15px;" class="font-bold flicker">&bull;</span> --}} <span class="font-bold">&bull;</span> <span class="forum-desc fw-bold"><a href="{{ url('/m/' . $message['id']) }}">{{ $message['datetime'] }}</a></span>
                                    @else
                                        <span class="font-bold">&bull;</span> <span class="forum-desc"><a href="{{ url('/m/' . $message['id']) }}">{{ $message['datetime'] }}</a></span>
                                    @endif
                                </div>
                                <div class="row forum-desc">
                                    <div class="col-xl-1 col-lg-2 col-md-5 col-sm-6 col-xs-12 p-0" style="min-width: 200px !important">
                                        От: <a class="post-a-color" href="{{ url('/user/' . $message['user']['id']) }}">{{ $message['user']['name'] }}</a>
                                    </div>
                                    {{-- <div class="col p-0">
                                        Кому: <a class="post-a-color" href="{{ url('/user/' . $message['user_to']['id']) }}">{{ $message['user_to']['name'] }}</a>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="col-1 d-flex justify-content-end align-items-center">
                                <form method='post' action='{{ url('/' . $message['id'] . '/hide') }}'>
                                    @csrf
                                    <input type="submit" class="btn-check btn-id" name="check[]" id="{{ $message['id'] }}" value="{{ $message['id'] }}">
                                    <label for="{{ $message['id'] }}">
                                        <i type="button" class="fa-solid fa-xmark"></i>
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="col mt-2">
                @include('message.inc.pagination', ['model' => $model['pagination']])
            </div>
        @endif
    </div>
@endsection
