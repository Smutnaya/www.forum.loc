@extends('layouts.topic')
@section('content')
    <div>
        @if ($errors->has('message'))
        <div class="alert alert-success mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ $errors->first('message') }}</div>
        @endif
        @if (Session::has('messageOk'))
            <div class="mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #009b7421 !important; border-color: #4f5c541c !important; border: 1px solid transparent;
            border-radius: 0.25rem; text-align: center;">{{ Session::get('messageOk') }}</div>
        @endif
        <h5 class="title-shadow mb-4">Новое сообщение</h5>
        @if (is_null($model['user']))
            <div class="error pb-1" style="color:red">Чтобы продолжить, выполните вход на сайт</div>
        @elseif ($model['user']['ban_message'])
            <div class="error pb-1" style="color:red">Отправка сообщений не доступна, обратитесь к Администрации</div>
        @else
            <form method='post' action='{{ url('/save_message') }}'>
                @csrf


                <p class="forum_comment mb-0">Кому:</p>
                <input type="text" name="to" id="to" maxlength="100" class="border border-1 input-text"></p>

                <p class="forum_comment mb-0 mt-3">Тема:</p>
                <input type="text" name="title" id="title" maxlength="100" title="*не более 100 символов" class="border border-1 input-text"></p>

                <p class="forum_comment mb-0 mt-1">Текст:</p>
                @include('inc.ckeditor')
                <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                    <a id="reset" class="btn btn-sm btn-dark btn-custom" href="{{ url('/message') }}">Отмена</a>
                    <input class="btn btn-sm btn-dark btn-custom" type="submit" value="Отправить">
                </div>
            </form>
        @endif
    </div>
@endsection
