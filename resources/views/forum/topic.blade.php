@extends('layouts.topic')
@section('title-block')
    @if (!is_null($model['forumTitle']))
        {{ $model['forumTitle'] }} - Создание новой темы
    @else
        Создание новой темы - Форум игры Времена Смуты
    @endif
@endsection
@section('content')

    <div class="pb-2 align-self-center" id="breadcrump">
        <?php
        echo htmlspecialchars_decode($model['breadcrump']);
        ?>
        <hr class="d-block my-2 d-lg-none hr-color">
    </div>
    @if ($errors->has('message'))
        <div class="alert alert-success mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ $errors->first('message') }}</div>
    @endif

    <form method='post' action='{{ url('/f/' . $forumId . '/t/save') }}'>
        @csrf

        <div class="scroll">
            <h5 class="title-shadow mb-4">Создание новой темы</h5>
            <p class="forum_comment mb-0">Тема:</p>
            <input type="text" name="title" id="title" maxlength="100" title="*не более 100 символов" class="border border-1 input-text"></p>

            @if ($model['user']['role_id'] > 1 || $model['editor'])
                <p class="forum_comment mb-0">Настройки:</p>
                <div class="btn-group col-12 mb-3 new-tema" role="group" aria-label="Basic checkbox toggle button group" style="height: 31px !important">
                    <input type="checkbox" class="btn-check" name="check[]" id="btncheck1" autocomplete="off" value="pin">
                    <label class="btn btn-outline-primary px-0" for="btncheck1"><i class="fa fa-thumb-tack forum-desc me-2"></i><span class="d-sm-inline d-none"> закрепить</span></label>

                    <input type="checkbox" class="btn-check" name="check[]" id="btncheck2" autocomplete="off" value="block">
                    <label class="btn btn-outline-primary px-0" for="btncheck2"><i class="fa-solid fa-lock forum-desc me-2"></i><span class="d-sm-inline d-none">закрыть</span></label>

                    @if ($model['section_id'] != 7)
                        <input type="checkbox" class="btn-check" name="check[]" id="btncheck3" autocomplete="off" value="hide" @if ($model['sections']['hide'] == 1) checked disabled @endif @if ($model['sections']['id'] == 6 && $forumId != 53) checked @endif>
                        <label class="btn btn-outline-primary px-0" for="btncheck3"><i class="fa-regular fa-eye-slash forum-desc me-2"></i><span class="d-sm-inline d-none">скрыть</span></label>

                        <input type="checkbox" class="btn-check" name="check[]" id="btncheck4" autocomplete="off" value="moder" @if ($model['sections']['moderation'] == 1) checked @endif>
                        <label class="btn btn-outline-primary px-0" for="btncheck4"><i class="fa-regular fa-hourglass forum-desc me-2"></i><span class="d-sm-inline d-none">модерация</span></label>
                    @endif

                    @if ($model['section_id'] == 5 && $forumId != 52 && ($model['user']['role_id'] > 11 || $model['user_alliance'] || $model['user_clan']))
                        <input type="checkbox" class="btn-check" name="check[]" id="btncheck5" autocomplete="off" value="private">
                        <label class="btn btn-outline-primary px-0" for="btncheck5"><i class="fa-solid fa-chess-rook forum-desc me-2"></i><span class="d-sm-inline d-none">приватная</span></label>
                    @endif
                </div>
            @endif
            @if ($model['sections']['id'] == 6 && $forumId != 53 && $model['news']->count() > 0)
                <p class="forum_comment mb-0">Категория новостей:</p>
                <div class="btn-group d-block mb-3 p-0 new-tema overflow-auto" style="max-height: 100px; border-color: #e3dbb7 !important;" role="group" aria-label="Basic checkbox toggle button group">
                    {{-- <select class="form-select new-tema" aria-label="Default select example">
                        <option selected>Категория</option>
                        @foreach ($model['news'] as $news)
                        <option value="{{ $news['id'] }}">{{ $news['title'] }}</option>
                        @endforeach
                    </select> --}}
                    @foreach ($model['news'] as $news)
                        <div class="form-check col-12 m-0 p-0 new-tema" style="height: 25px !important; width: 100% !important;">
                            <input type="radio" class="btn-check" name="news" autocomplete="off" id="n{{ $news['id'] }}" value="{{ $news['id'] }}">
                            <label class="btn btn-outline-primary px-0" for="n{{ $news['id'] }}" style="padding: 0 !important">
                                <span class="d-sm-inline"> {{ $news['title'] }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($model['sections']['hide'] == 1)
                <p class="small" style="color:#6a0000 !important">* возможно создавать только скрытые темы</p>
            @endif
            @if ($model['sections']['moderation'] == 1)
                <p class="small" style="color:#6a0000 !important">* премодерация тем на форуме</p>
            @endif
            <p class="forum_comment mb-0">Текст:</p>
            @include('inc.ckeditor')
            <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                <a id="reset" class="btn btn-sm btn-dark btn-custom" href="{{ url('/f/' . $forumId) }}">Отмена</a>
                <input class="btn btn-sm btn-dark btn-custom" type="submit" value="Добавить">
            </div>
    </form>
    </div>
@endsection
