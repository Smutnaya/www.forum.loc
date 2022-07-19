@extends('layouts.topic')
@section('content')
    @if (is_null($model['post']))
        <div class="text-danger">Пост не найден!</div>
    @else
        @if ($model['post']['user_id'] == $model['user']['id']  && time() <= ($model['post']['datetime'] + 3600))
            @if (!is_null($model['breadcrump']))
                @include('inc.breadcrump', ['posts' => $model['breadcrump']])
            @endif

            @if ($errors->has('message'))
                <div class="error pb-1 text-danger">{{ $errors->first('message') }}</div>
            @endif

            <form method='post' action='{{ url('/p/' . $model['post']['id'] . '/save') }}'>
                @csrf
                <div>
                    <h5 class="title-shadow mb-4">Редактирование поста</h5>
                    <p class="forum_comment mb-0">Настройки:</p>
                    <div class="btn-group col-12 mb-3 new-tema" role="group" aria-label="Basic checkbox toggle button group" style="height: 31px !important">
                        <input type="checkbox" class="btn-check" name="check[]" id="btncheck3" autocomplete="off"
                            value="hide" @if ($model['post']['hide'] == 1) checked @endif
                            @if ($model['topic']['hide'] == 1) checked disabled @endif>
                        <label class="btn btn-outline-primary px-0" for="btncheck3"><i
                                class="fa-regular fa-eye-slash forum-desc me-2"></i><span
                                class="d-sm-inline d-none">скрыть</span></label>

                        <input type="checkbox" class="btn-check" name="check[]" id="btncheck4" autocomplete="off"
                            value="moder" @if ($model['post']['moderation'] == 1) checked @endif>
                        <label class="btn btn-outline-primary px-0" for="btncheck4"><i
                                class="fa-regular fa-hourglass forum-desc me-2"></i><span
                                class="d-sm-inline d-none">модерация</span></label>
                    </div>
                    @if ($model['topic']['hide'] == 1)
                        <p class="small" style="color:#660000 !important">* возможно публиковать только скрытые посты</p>
                    @endif
                    <p class="forum_comment mb-0">Текст:</p>
                    <textarea name="text" type="text" id="text" onkeypress="if(event.keyCode == 13) return false;">{{ $model['post']['text'] }}</textarea>

                    <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                        <a id="reset" class="btn btn-sm btn-dark btn-custom ">Очистить</a>
                        <input  class="btn btn-sm btn-dark btn-custom" type="submit" value="Добавить">
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