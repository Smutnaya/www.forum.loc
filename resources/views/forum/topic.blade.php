
@extends('layouts.topic')
@section('content')
<div class="pb-2 align-self-center" id="breadcrump">

    <?php
    echo htmlspecialchars_decode($model['breadcrump']);
    ?>
    <hr class="d-block d-lg-none hr-color">
</div>

@if($errors->has('message'))
    <div class="error pb-1" style="color:red">{{ $errors->first('message') }}</div>
@endif

<form method='post' action='{{ url('/f/'.$forumId.'/t/save')}}'>
    @csrf

    <div >
        <h5 class="title-shadow mb-4">Создание новой темы</h5>
        <p class="forum_comment mb-0">Тема:</p>
        <input type="text" name="title" id="title" class="border border-1 input-text"></p>
        <p class="forum_comment mb-0">Настройки:</p>
        <div class="btn-group col-12 mb-3 new-tema" role="group" aria-label="Basic checkbox toggle button group" style="height: 31px !important">

            <input type="checkbox" class="btn-check" name="check[]" id="btncheck1" autocomplete="off" value="pin">
            <label class="btn btn-outline-primary px-0" for="btncheck1"><i class="fa fa-thumb-tack forum-desc me-2"></i><span class="d-sm-inline d-none"> закрепить</span></label>

            <input type="checkbox" class="btn-check" name="check[]" id="btncheck2" autocomplete="off" value="block">
            <label class="btn btn-outline-primary px-0" for="btncheck2"><i class="fa-solid fa-lock forum-desc me-2"></i><span class="d-sm-inline d-none">закрыть</span></label>

            <input type="checkbox" class="btn-check" name="check[]" id="btncheck3" autocomplete="off" value="hide" @if($model['sections']['hide'] == 1) checked disabled @endif>
            <label class="btn btn-outline-primary px-0" for="btncheck3"><i class="fa-regular fa-eye-slash forum-desc me-2"></i><span class="d-sm-inline d-none">скрыть</span></label>

            <input type="checkbox" class="btn-check" name="check[]" id="btncheck4" autocomplete="off" value="moder" @if($model['sections']['moderation'] == 1 ) checked  disabled @endif>
            <label class="btn btn-outline-primary px-0" for="btncheck4"><i class="fa-regular fa-hourglass forum-desc me-2"></i><span class="d-sm-inline d-none">модерация</span></label>
        </div>
        @if($model['sections']['hide'] == 1) <p class="small" style="color:#660000 !important">* возможно создавать только скрытые темы</p>@endif
        @if($model['sections']['moderation'] == 1 ) <p class="small" style="color:#660000 !important">* премодерация тем на форуме</p>@endif
        <p class="forum_comment mb-0">Текст:</p>
        @include('inc.ckeditor')
        <input type="submit" value="Добавить">
</form>
    </div>
    <input id="reset" type="submit" value="Очистить">

@endsection

