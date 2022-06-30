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
                <div class="col text-break d-flex justify-content-start align-items-center py-1 " id="title">
                    <h4 class="m-0 ">{{ $model['topic']['title'] }} </h4>
                    <i id="topic-edit" class="fa-solid fa-pencil ms-2 mt-1" style="color: #989e9a;"></i>
                </div>
            </div>

            @include('topic.inc.topicEdit', ['model' => $model])
            @if (is_null($model['topic']) || is_null($model['posts']) || $model['posts']->count() == 0)
        <div>Постов не найдено</div>
        @else
            @include('topic.inc.post', ['model' => $model])
            @endif
            <div class="col d-grid gap-2 d-flex justify-content-end mb-2">
                <a id="btn-post-field" class="btn btn-sm btn-dark btn-custom ">Ответить</a>
            </div>
            <div id="menu-post-field" style="display:none">
                <div class="row">
                    <form method='post' action='{{ url('t/' . $model['topic']['id'] . '/post') }}'>
                        @csrf
                        @include('inc.ckeditor')

                        <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                            <a id="reset" class="btn btn-sm btn-dark btn-custom ">Очистить</a>
                            <input class="btn btn-sm btn-dark btn-custom" type="submit" value="Добавить">
                        </div>
                    </form>

                </div>
            </div>
        @endif
    </div>
    <br>
    <script>
        $(document).ready(function() {
            $('#btn-post-field').click(function(e) {
                e.preventDefault();
                // TODO: scroll down
                $('#menu-post-field').show();
                $('#btn-post-field').hide();
                $('.cke_wysiwyg_frame').contents().find('.cke_editable').focus();
            });
            $('#topic-edit').click(function(e) {
                e.preventDefault();
                // TODO: scroll down
                $('#topic-edit-field').show();
            });
            $('#topic-edit-hide').click(function(e) {
                e.preventDefault();
                // TODO: scroll down
                $('#topic-edit-field').hide();
            });
        });
        $('#reset').click(function(e) {
            CKEDITOR.instances.text.setData("");
        });
    </script>
@endsection
