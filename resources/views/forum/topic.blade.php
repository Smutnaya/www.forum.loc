
@extends('layouts.forum')
@section('content')

@if($errors->has('message'))
    <div class="error" style="color:red">{{ $errors->first('message') }}</div>
@endif

<form method='post' action={{ url('/f/'.$forumId.'/topic/save')}}>
    @csrf

        <div class="topicNew">
            <p><label>Название топика: </label>
                <input type="text" name="title" id="title"></p>
            <p><label>Текст: </label>
                <input type="text" name="text" id="text"></p>
            <p><input type="submit" value="Отправить">
                <input type="reset" value="Очистить"></p>
        </div>
    </form>

@endsection

    {{-- @if(is_null($model['topic']))
<div>Новый топик</div>
@else


@if($errors->has('message'))
    <div class="error" style="color:red">{{ $errors->first('message') }}</div>
@endif

<form method='post' action={{ url('/f/'.$forumId.'/topic/save')}}>
    @csrf

        <div class="topicNew">
            <p><label>Название топика: </label>
                <input type="text" name="title" id="title"></p>
            <p><label>Текст: </label>
                <input type="text" name="text" id="text"></p>
            <p><input type="submit" value="Отправить">
                <input type="reset" value="Очистить"></p>
        </div>
</form>
@endif --}}
