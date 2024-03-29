@extends('layouts.topic')
@section('title-block')
    @if (!is_null($model['topic']))
        {{ $model['topic']['title'] }} - Форум игры Времена Смуты
    @else
        Форум игры Времена Смуты
    @endif
@endsection
@section('content')
    {{-- @dd($model) --}}
    <div class="conteiner">
        @if (is_null($model['topic']))
            <div class="my-3 mb-5 centre error" style="color:red">Тема не найдена</div>
        @elseif ($model['visit_forum'] == false && !$model['user_clan'] && !$model['user_alliance'])
            <div class="my-3 mb-5 centre error" style="color:red">Отсутствуют права для просмотра темы</div>
        @else
            @if ($errors->has('message'))
                <div class="alert alert-success mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ $errors->first('message') }}</div>
            @endif
            @include('inc.breadcrump', ['posts' => $model['breadcrump']])
            @include('topic.inc.topicInf', ['model' => $model])

            @include('topic.inc.pagination', ['model' => $model['pagination']])

            @if (is_null($model['topic']) || is_null($model['posts']) || $model['posts']->count() == 0)
                <div class="my-3 mb-5 centre error post shadow-sm p-0"><span class="p-1" style="background: #ffffde7a">Постов не найдено</span>
                </div>
            @else
                @if ($model['topicEdit'])
                    @include('topic.inc.topicEdit', ['model' => $model])
                @endif
                @if ($model['topicMove'])
                    @include('topic.inc.topicMove', ['model' => $model])
                @endif


                @include('topic.inc.post', ['model' => $model])
            @endif
            <div class="d-flex justify-content-between">
                <div class="col mb-2">
                    @if ($model['posts']->count() > 0)
                        @include('topic.inc.pagination', ['model' => $model['pagination']])
                    @endif
                </div>


                @if (!is_null($model['user']))
                    @if ((!$model['topic']['block'] && !$model['userBan'] && $model['topic']['forum_id'] != 38 && $model['topic']['forum_id'] != 39) || $model['newPost'] || $model['user_alliance_moder'] || $model['user_clan_moder'])
                        @if ($model['topic']['forum_id'] != 1 && $model['topic']['forum_id'] != 2 && $model['topic']['forum_id'] != 42)
                            <div class="col d-grid gap-2 d-inline-flex justify-content-end mb-2">
                                @if ($model['topic']['block'])
                                    <span class="centre" style="font-size: 11px; color: #6a0000">*Тема закрыта</span>
                                @endif
                                <a id="btn-post-field" class="btn btn-sm btn-dark btn-custom shadow-sm">Ответить</a>
                            </div>
            </div>
            <div id="menu-post-field" style="display:none">
                <div class="row">
                    <form method='post' action='{{ url('t/' . $model['topic']['id'] . '/post') }}'>
                        @csrf
                        @include('inc.ckeditor')
                        <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                            <a id="reset" class="btn btn-sm btn-dark btn-custom shadow-sm">Очистить</a>
                            <input class="btn btn-sm btn-dark btn-custom shadow-sm" type="submit" value="Добавить">
                        </div>
                    </form>
                </div>
            </div>
        @elseif ($model['topic']['forum_id'] == 42)
        @if($model['user']['role_id'] > 5 || $model['moder'] && $model['posts']->count() > 0)
            <div class="col d-grid gap-2 d-inline-flex justify-content-end mb-2">
                @if ($model['topic']['block'])
                    <span class="centre" style="font-size: 11px; color: #6a0000">*Тема закрыта</span>
                @endif
                <a id="btn-post-field" class="btn btn-sm btn-dark btn-custom shadow-sm">Ответить</a>
            </div>
    </div>
    <div id="menu-post-field" style="display:none">
        <div class="row">
            <form method='post' action='{{ url('t/' . $model['topic']['id'] . '/post') }}'>
                @csrf
                @include('inc.ckeditor')
                <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                    <a id="reset" class="btn btn-sm btn-dark btn-custom shadow-sm">Очистить</a>
                    <input class="btn btn-sm btn-dark btn-custom shadow-sm" type="submit" value="Добавить">
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="col d-grid gap-2 d-inline-flex justify-content-end mb-2">
            <span class="centre" style="color: #6a0000">Ожидайте ответа модератора</span>
    </div>

    @endif
@elseif (($model['topic']['forum_id'] == 1 && $model['user']['role_id'] > 10) || $model['moder'])
    <div class="col d-grid gap-2 d-inline-flex justify-content-end mb-2">
        @if ($model['topic']['block'])
            <span class="centre" style="font-size: 11px; color: #6a0000">*Тема закрыта</span>
        @endif
        <a id="btn-post-field" class="btn btn-sm btn-dark btn-custom shadow-sm">Ответить</a>
    </div>
    </div>
    <div id="menu-post-field" style="display:none">
        <div class="row">
            <form method='post' action='{{ url('t/' . $model['topic']['id'] . '/post') }}'>
                @csrf
                @include('inc.ckeditor')
                <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                    <a id="reset" class="btn btn-sm btn-dark btn-custom shadow-sm">Очистить</a>
                    <input class="btn btn-sm btn-dark btn-custom shadow-sm" type="submit" value="Добавить">
                </div>
            </form>
        </div>
    </div>
@elseif (($model['topic']['forum_id'] == 2 && $model['user']['role_id'] > 8) || $model['moder'])
    <div class="col d-grid gap-2 d-inline-flex justify-content-end mb-2">
        @if ($model['topic']['block'])
            <span class="centre" style="font-size: 11px; color: #6a0000">*Тема закрыта</span>
        @endif
        <a id="btn-post-field" class="btn btn-sm btn-dark btn-custom shadow-sm">Ответить</a>
    </div>
    </div>
    <div id="menu-post-field" style="display:none">
        <div class="row">
            <form method='post' action='{{ url('t/' . $model['topic']['id'] . '/post') }}'>
                @csrf
                @include('inc.ckeditor')
                <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                    <a id="reset" class="btn btn-sm btn-dark btn-custom shadow-sm">Очистить</a>
                    <input class="btn btn-sm btn-dark btn-custom shadow-sm" type="submit" value="Добавить">
                </div>
            </form>
        </div>
    </div>
    @endif
@elseif ($model['userBan'])
    <span class="centre p-2" style="color: #6a0000">Пользователь заблокирован в теме!</span>
@else
    <span class="centre p-2 text-secondary">Тема закрыта для новых публикаций</span>
    @endif
@else
    <span class="centre p-2 text-secondary">Войдите на сайт, чтобы оставить ответ в теме</span>
    @endif
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
                    .data('inf') + '</div></blockquote><br>');
                // CKEDITOR.instances.text.setData('<blockquote style="background: #f1e9c28f; color: #4a4741; margin: 0 0 1rem 1rem; padding: 5px 13px !important; font-size: 14px; font-style: italic; font-family: "Open Sans", "Arial", Helvetica, serif !important; border-style: solid; border-color: #afa4843d; border-width: 0.005rem; border-radius: 0px 15px !important;">'+$(this).data('text')+'<div style="text-align: right; font-size: 9pt;">'+$(this).data('inf')+'</div></blockquote>');
            });
        });
        $(document).ready(function() {
            $('[data-bs-toggle="popover"]').popover();
        });

        CKEDITOR.on('dialogDefinition', function(ev) {
            var dialogName = ev.data.name;
            var dialogDefinition = ev.data.definition;
            if (dialogName == 'link') {
                var targetTab = dialogDefinition.getContents('target');
                var targetField = targetTab.get('linkTargetType');
                targetField['default'] = '_blank';
            }
        });

        // var timer;
        // var isPaused = false;

        // $(window).on('wheel', function() {
        //     isPaused = true;
        //     clearTimeout(timer);
        //     timer = window.setTimeout(function() {
        //         isPaused = false;
        //     }, 10000000);
        // });

        // window.setInterval(function() {
        //     if (!isPaused) {
        //         window.scrollTo(0, document.body.scrollHeight);
        //     }
        // }, 500);

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
