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
                    <i id="topic-edit" class="fa-solid fa-pencil ms-2 mt-1" style="color: #989e9a;"
                        title="Редактировать тему"></i>
                    {{-- <i class="fa-regular fa-folder ms-2 mt-1" style="color: #989e9a;"></i> --}}
                    <i id="topic-move" class="fa-solid fa-truck-arrow-right ms-3 mt-1" style="color: #989e9a;" title="Переместить тему" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>

                </div>
            </div>

            @include('topic.inc.topicMove', ['model' => $model])
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
            $('#topic-move').click(function(e) {
                e.preventDefault();
                // TODO: scroll down
                $('#topic-move-field').show();
            });
            $('#topic-move-hide').click(function(e) {
                e.preventDefault();
                // TODO: scroll down
                $('#topic-move-field').hide();
            });
        });
        $('#reset').click(function(e) {
            CKEDITOR.instances.text.setData("");
        });

    </script>
@endsection
