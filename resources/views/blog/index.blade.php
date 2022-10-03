@extends('layouts.topic')
@section('title-block')
    @if (!is_null($model['topic']))
        {{ $model['topic']['title'] }} - Форум игры Времена Смуты
    @else
        Газеты и блоги игры Времена Смуты
    @endif
@endsection
@section('content')
    {{-- @dd($model) --}}
    <div class="conteiner">
        @if (is_null($model['topic']))
            <div class="my-3 mb-5 centre error" style="color:red">Тема не найдена</div>
        @elseif ($model['visit_forum'] == false)
            <div class="my-3 mb-5 centre error" style="color:red">Отсутствуют права для просмотра темы</div>
        @else
            @if ($errors->has('message'))
                <div class="alert alert-success mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ $errors->first('message') }}</div>
            @endif
            @include('inc.breadcrump', ['posts' => $model['breadcrump']])
            <div class="row m-0">
                <div class="col p-0 text-break d-flex justify-content-start align-items-center py-1" id="title">
                    <h4 class="m-0 "><span class="title-shadow"><span class="text-secondary" title="ID темы">
                                @if ($model['topicEdit'] || $model['editor'])
                                    [{{ $model['topic']['id'] }}]
                                @endif
                            </span>{{ $model['topic']['title'] }} </span></h4>
                    @if ($model['topicEdit'] || $model['editor'])
                        <i onclick="toggleEdit()" type="button" id="topic-edit" class="fa-solid fa-pencil ms-2 mt-1" style="color: #989e9a;" title="Редактировать тему"></i>
                    @endif
                    @if ($model['topicMove'])
                        <i onclick="toggleMove()" type="button" id="topic-move" class="fa-solid fa-truck-arrow-right ms-3 mt-1" style="color: #989e9a;" title="Переместить тему" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
                    @endif
                </div>
                @if (!is_null($model['topic']['news_id']))
                    <div class="forum-desc mb-1">#{{ $model['topic']['news_title'] }}</div>
                @endif
            </div>
            @if (is_null($model['topic']) || is_null($model['posts']) || $model['posts']->count() == 0)
                <div class="my-3 mb-5 centre error post shadow-sm p-0"><span class="p-1" style="background: #ffffde7a">Постов не найдено</span>
                </div>
            @else
                @if ($model['topicEdit'] || $model['editor'])
                    @include('topic.inc.topicEdit', ['model' => $model])
                @endif
                @if ($model['topicMove'])
                    @include('topic.inc.topicMove', ['model' => $model])
                @endif
                {{-- @include('topic.inc.pagination', ['model' => $model['pagination']]) --}}

                @include('topic.inc.post', ['model' => $model])
            @endif
            <div class="col-12"></div>
            <div class="d-flex justify-content-end">
                @if (!is_null($model['user']))
                    @if ((!$model['topic']['block'] && !$model['userBan']) || $model['newPost'] || $model['editor'])
                        <div class="col d-grid gap-2 d-inline-flex justify-content-end mb-2">
                            @if ($model['topic']['block'])
                                @if ($model['newPost'] || $model['editor'])
                                    <span class="centre p-2" style="font-size: 11px; color: #6a0000">*Комментарии к теме отключены</span>
                                @endif
                            @endif
                            <a id="btn-post-field" class="btn btn-sm btn-dark btn-custom shadow-sm" style="width: 100px !important;">Комметарий</a>
                        </div>
            </div>
            <div id="menu-post-field" style="display:none">
                <div class="row">
                    <form method='post' action='{{ url('t/' . $model['topic']['id'] . '/comment') }}'>
                        @csrf
                        @include('blog.inc.ckeditor')
                        <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                            <a id="reset" class="btn btn-sm btn-dark btn-custom shadow-sm">Очистить</a>
                            <input class="btn btn-sm btn-dark btn-custom shadow-sm" type="submit" value="Добавить">
                        </div>
                    </form>
                </div>
            </div>
        @elseif ($model['userBan'])
            <span class="centre p-2" style="color: #6a0000">Пользователь заблокирован в теме!</span>
        @else
            <span class="centre p-2 text-secondary">Комментарии к теме отключены</span>
        @endif
    @else
        <span class="centre p-2 text-secondary">Войдите на сайт, чтобы оставить комментарий в теме</span>
        @endif
    </div>
    @include('blog.inc.comments', ['model' => $model])
    @endif
    </div>

    <script>
        $(document).ready(function() {
            $('#btn-post-field').click(function(e) {
                e.preventDefault();
                // TODO: scroll down
                $('#menu-post-field').show();
                $('#btn-post-field').hide();
                $('.cke_wysiwyg_frame').contents().find('.cke_editable').focus();
            });
            // $('#topic-edit').click(function(e) {
            //     e.preventDefault();
            //     // TODO: scroll down
            //     $('#topic-edit-field').show();
            // });
            $('#topic-edit-hide').click(function(e) {
                e.preventDefault();
                // TODO: scroll down
                $('#topic-edit-field').hide();
            });
            // $('#topic-move').click(function(e) {
            //     e.preventDefault();
            //     // TODO: scroll down
            //     $('#topic-move-field').show();
            // });
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
            $('.quote').on('click', function(e) {
                e.preventDefault();
                $('#menu-post-field').show();
                $('#btn-post-field').hide();
                $('.cke_wysiwyg_frame').contents().find('.cke_editable').focus();
                CKEDITOR.instances.text.setData(
                    '<blockquote style=\'background: #f1e9c28f; color: #4a4741; margin: 0 0 1rem 1rem; padding: 5px 13px !important; font-size: 14px; font-style: italic; font-family: "Open Sans", "Arial", Helvetica, serif !important; border-style: solid; border-color: #afa4843d; border-width: 0.005rem; border-radius: 0px 15px !important;\'>' +
                    $(this).data('text') + '<div style="text-align: right; font-size: 9pt;">' + $(this)
                    .data('inf') + '</div></blockquote>');
                // CKEDITOR.instances.text.setData('<blockquote style="background: #f1e9c28f; color: #4a4741; margin: 0 0 1rem 1rem; padding: 5px 13px !important; font-size: 14px; font-style: italic; font-family: "Open Sans", "Arial", Helvetica, serif !important; border-style: solid; border-color: #afa4843d; border-width: 0.005rem; border-radius: 0px 15px !important;">'+$(this).data('text')+'<div style="text-align: right; font-size: 9pt;">'+$(this).data('inf')+'</div></blockquote>');
            });
        });
        $(document).ready(function() {
            $('[data-bs-toggle="popover"]').popover();
        });

        var edit = document.getElementById('topic-edit-field');
        var move = document.getElementById('topic-move-field');

        function toggleEdit() {
            if (edit.style.display == "none") {
                edit.style.display = "";
                move.style.display = "none";
            } else {
                edit.style.display = "none";
            }
        }

        function toggleMove() {
            if (move.style.display == "none") {
                move.style.display = "";
                edit.style.display = "none";
            } else {
                move.style.display = "none";
            }
        }
    </script>
@endsection
