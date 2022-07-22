@extends('layouts.topic')
@section('content')

    <div class="conteiner">
        @if (is_null($model['topic']))
            <div class="my-3 mb-5 centre error" style="color:red">Тема не найдена</div>
        @else
            @if ($errors->has('message'))
                <div class="error" style="color:red">{{ $errors->first('message') }}</div>
            @endif
            @include('inc.breadcrump', ['posts' => $model['breadcrump']])
            <div class="row">
                <div class="col text-break d-flex justify-content-start align-items-center py-1 " id="title">
                    <h4 class="m-0 ">{{ $model['topic']['title'] }} </h4>
                    <i type="button" id="topic-edit" class="fa-solid fa-pencil ms-2 mt-1" style="color: #989e9a;"
                        title="Редактировать тему"></i>
                    {{-- <i class="fa-regular fa-folder ms-2 mt-1" style="color: #989e9a;"></i> --}}
                    <i type="button" id="topic-move" class="fa-solid fa-truck-arrow-right ms-3 mt-1"
                        style="color: #989e9a;" title="Переместить тему" data-bs-toggle="modal"
                        data-bs-target="#exampleModal"></i>

                </div>
            </div>
            @if (is_null($model['topic']) || is_null($model['posts']) || $model['posts']->count() == 0)
                <div class="my-3 mb-5 centre error">Постов не найдено</div>
            @else
                @include('topic.inc.topicMove', ['model' => $model])
                @include('topic.inc.topicEdit', ['model' => $model])
                @include('topic.inc.pagination', ['model' => $model['pagination']])
                @include('topic.inc.post', ['model' => $model])

            @endif
            <div class="d-flex justify-content-between">
            <div class="col mb-2">
                @include('topic.inc.pagination', ['model' => $model['pagination']])
            </div>
            @if (!is_null($model['user']))
                    <div class="col d-grid gap-2 d-inline-flex justify-content-end mb-2">
                        <a id="btn-post-field" class="btn btn-sm btn-dark btn-custom ">Ответить</a>
                    </div>
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
                @else
                    <span class="centre p-2 text-secondary">Войдите на сайт, чтобы оставить ответ в теме</span>
                @endif
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
        $(document).ready(function() {
            $('[data-bs-toggle="popover"]').popover();
        });
    </script>
@endsection
