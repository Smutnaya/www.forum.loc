@extends('layouts.topic')
@section('title-block')
    Редактирование ответа - Форум игры Времена Смуты
@endsection
@section('content')
    @if (is_null($model['post']))
        <div class="text-danger">Пост не найден!</div>
    @else
        @if ($model['postEdit'] || $model['postModer'] || $model['editor'] || $model['user_alliance_moder'] || $model['user_clan_moder'])
            @if (!is_null($model['breadcrump']))
                @include('inc.breadcrump', ['posts' => $model['breadcrump']])
            @endif

            @if ($errors->has('message'))
                <div class="alert alert-success mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ $errors->first('message') }}</div>
            @endif

            <form method='post' action='{{ url('/p/' . $model['post']['id'] . '/save/' . $model['page']) }}'>
                @csrf
                <div>
                    <h5 class="title-shadow mb-4">Редактирование ответа</h5>
                    @if ($model['user']['role_id'] > 1 || $model['editor'] || $model['user_alliance_moder'] || $model['user_clan_moder'])
                        @if ($model['section_id'] != 7 && $model['section_id'] != 3)
                            <p class="forum_comment mb-0">Настройки:</p>
                            <div class="btn-group col-12 mb-3 new-tema" role="group" aria-label="Basic checkbox toggle button group" style="height: 31px !important">
                                <input type="checkbox" class="btn-check" name="check[]" id="btncheck3" autocomplete="off" value="hide" @if ($model['post']['hide'] == 1) checked @endif>
                                <label class="btn btn-outline-primary px-0" for="btncheck3"><i class="fa-regular fa-eye-slash forum-desc me-2">
                                    </i><span class="d-sm-inline d-none">скрыть</span></label>

                                <input type="checkbox" class="btn-check" name="check[]" id="btncheck4" autocomplete="off" value="moder" @if ($model['post']['moderation'] == 1) checked @endif>
                                <label class="btn btn-outline-primary px-0" for="btncheck4"><i class="fa-regular fa-hourglass forum-desc me-2"></i>
                                    <span class="d-sm-inline d-none">модерация</span></label>
                            </div>
                        @endif
                    @endif
                    @if ($model['user']['role_id'] > 8 && $model['section_id'] == 3)
                        <p class="forum_comment mb-0">Настройки:</p>
                        <div class="btn-group col-12 mb-3 new-tema" role="group" aria-label="Basic checkbox toggle button group" style="height: 31px !important">
                            <input type="checkbox" class="btn-check" name="check[]" id="btncheck3" autocomplete="off" value="hide" @if ($model['post']['hide'] == 1) checked @endif>
                            <label class="btn btn-outline-primary px-0" for="btncheck3"><i class="fa-regular fa-eye-slash forum-desc me-2">
                                </i><span class="d-sm-inline d-none">скрыть</span></label>

                            <input type="checkbox" class="btn-check" name="check[]" id="btncheck4" autocomplete="off" value="moder" @if ($model['post']['moderation'] == 1) checked @endif>
                            <label class="btn btn-outline-primary px-0" for="btncheck4"><i class="fa-regular fa-hourglass forum-desc me-2"></i>
                                <span class="d-sm-inline d-none">модерация</span></label>
                        </div>
                    @endif
                    @if ($model['topic']['hide'] == 1)
                        <p class="small" style="color:#6a0000 !important">* тема скрыта</p>
                    @endif
                    <p class="forum_comment mb-0">Текст:</p>
                    <textarea name="text" type="text" id="text" onkeypress="if(event.keyCode == 13) return false;">{{ $model['post']['text'] }}</textarea>

                    <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                        <a id="reset" class="btn btn-sm btn-dark btn-custom ">Очистить</a>
                        <input class="btn btn-sm btn-dark btn-custom" type="submit" value="Сохранить">
                    </div>
            </form>
            </div>
        @else
            <div class="text-danger">У Вас нет прав для редактирования!</div>
        @endif
    @endif

    <script>
        CKEDITOR.addCss(".cke_editable{cursor:text; font-size: 13px; font-family: Arial, sans-serif;} p{margin:1;}")
        CKEDITOR.replace('text', {
            customConfig: '/ckeditor/custom/ckeditor_config.js',
            removePlugins: ['elementspath', 'autogrow', 'uploadimage'],
            filebrowserUploadUrl: "{{ route('upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form',
            allowedContent: true
        });

        $('#reset').click(function(e) {
            CKEDITOR.instances.text.setData("");
        });
        $('#reset').click(function(e) {
            CKEDITOR.instances.text.setData("");
        });
    </script>

@endsection
