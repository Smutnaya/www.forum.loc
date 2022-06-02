
@extends('layouts.forum')
@section('content')
<?php
echo htmlspecialchars_decode($model['breadcrump']);
?>
@if(is_null($model['topic']))
<div>Tema ne najdena</div>
@else

@if($errors->has('message'))
    <div class="error" style="color:red">{{ $errors->first('message') }}</div>
@endif



<div>{{ $model['topic']['title'] }}</div>
<div>{{ $model['topic']['text'] }}</div>
@foreach($model['posts'] as $post)
<div class="text">{{ $post['text'] }}</div>
@endforeach

<form method='post' action={{ url('t/'.$model['topic']['id'].'/post')}}>
    @csrf
    <div>
        <p><label>Новый ответ: </label>
            <input type="text" name="text" id="text" onkeypress="if(event.keyCode == 13) return false;"></p>
        <p><input type="submit" value="Добавить">
            <input type="reset" value="Очистить"></p>
    </div>
</form>
@endif

@endsection
