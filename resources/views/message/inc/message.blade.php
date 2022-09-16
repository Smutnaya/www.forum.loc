@extends('layouts.topic')
@section('content')
    <div class="conteiner-fluid">
        @if ($errors->has('message'))
            <div class="alert alert-success mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ $errors->first('message') }}</div>
        @endif
        @if (Session::has('messageOk'))
            <div class="mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #009b7421 !important; border-color: #4f5c541c !important; border: 1px solid transparent;
            border-radius: 0.25rem; text-align: center;">{{ Session::get('messageOk') }}</div>
        @endif
        <div class="row mb-2">
            <div class="pb-1 col-12" id="title">
                <a href="{{ url('/message') }}">
                    <h4 class="title-shadow"><i class="fa-solid fa-angle-left align-middle" style="padding-bottom: 3px; font-size: 15px;"></i> Сообщения</h4>
                </a>
            </div>
        </div>
        <div class="border border-ligh shadow-sm mb-3" style="background: #faf2cc;">
            @if (is_null($model['message']))
                <div class="my-3 mb-5 centre">Сообщение не найдено</div>
            @else
                <div class="p-2">
                    <div>
                        Тема: <span class="fw-bold">
                            {{ $model['message']['title'] }}
                        </span>
                    </div>
                    <div>
                        От: <a class="fw-bold text-break" href="{{ url('/user/' . $model['message']['user']['id']) }}">{{ $model['message']['user']['name'] }}</a>
                    </div>
                    <div class="forum-desc m-0" style="font-size: 0.8rem !important;">
                        {{ $model['message']['datetime'] }}
                    </div>
                </div>
                <div class="text-centre overflow-hidden py-4 p-2" style="background: #ffffe0; border-top: 1px solid #dee2e694 !important;">
                    <?php
                    echo htmlspecialchars_decode($model['message']['text']);
                    ?>
                </div>
        </div>

        @if (!is_null($model['user']))
            @if (!$model['user']['ban_message'])
                <div class="col d-grid gap-2 mb-2 d-flex justify-content-end">
                    <a id="btn-post-field" class="quote btn btn-sm btn-dark btn-custom shadow-sm" style="width: 95px !important;" data-text="<div>{{ $model['message']['text'] }}</div>" data-inf="<div class='mt-1 forum-desc text-end'><span class='fw-bold text-end'>{{ $model['message']['user']['name'] }}</span>&nbsp;&middot;&nbsp;<span class='text-end'>{{ $model['message']['datetime'] }}</span></div>"><i class="fa fa-share" aria-hidden="true"></i> &nbsp; Ответить</a>
                </div>

                <div id="menu-post-field" style="display:none">
                    <div class="row">
                        <form method='post' action='{{ url('/' . $model['message']['id'] . '/reply') }}'>
                            @csrf
                            @include('inc.ckeditor')
                            <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                                <a id="reset" class="btn btn-sm btn-dark btn-custom shadow-sm">Очистить</a>
                                <input class="btn btn-sm btn-dark btn-custom shadow-sm" type="submit" value="Ответить">
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <span class="p-2 d-flex justify-content-end" style="color: #6a0000">Отправка сообщений не доступна, обратитесь к Администрации</span>
            @endif
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
            $('#topic-edit-hide').click(function(e) {
                e.preventDefault();
                // TODO: scroll down
                $('#topic-edit-field').hide();
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
    </script>
@endsection
