@extends('layouts.topic')
@section('content')
    <div class="conteiner">

        @if (is_null($model['topic']))
            <div>Тема не найдена</div>
        @else
            @if ($errors->has('message'))
                <div class="error" style="color:red">{{ $errors->first('message') }}</div>
            @endif
            @include('inc.breadcrump', ['posts' => $model['breadcrump']])
            <div class="row">
                <div class="col-1 d-flex justify-content-start align-items-center pb-1" id="title">
                    <h4 class="m-0">{{ $model['topic']['title'] }} </h4>
                    <i class="fa-solid fa-pencil ms-2 mt-1" style="color: #989e9a;"></i>
                </div>
            </div>
            @include('topic.inc.post', ['model' => $model])

            <form method='post' action={{ url('t/' . $model['topic']['id'] . '/post') }}>
                @csrf
                <label>Новый ответ: </label>
                @include('inc.ckeditor')
                <input type="submit" value="Добавить">
            </form>
            <input id="reset" type="submit" value="Очистить">
        @endif
    </div>

@endsection
