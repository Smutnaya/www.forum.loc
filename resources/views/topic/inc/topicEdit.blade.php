<div class="row my-1 mx-auto ">
    <div id="topic-edit-field" style="display:none; background: #f6f0cc;" class="borderborder border-ligh shadow-sm">
        @if (is_null($model['posts']))
            <div class="text-danger">Пост не найден!</div>
        @else
            @if (is_null($model['topic']) || is_null($model['posts']) || is_null($model['user']) || is_null($model['forum']))
                <div class="text-danger">Что-то пошло не так</div>
            @else
                @if ($model['topicEdit'] || $model['editor'])
                    @if ($errors->has('message'))
                        <div class="alert alert-success mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ $errors->first('message') }}</div>
                    @endif
                    <form method='post' action='{{ url('/t/' . $model['topic']['id'] . '/edit') }}'>
                        @csrf
                        <div class="my-3">
                            <p class="forum_comment mb-0">Название темы:</p>
                            <input type="text" name="title" id="title" maxlength="100" title="*не более 100 символов" class="border border-1 input-text mb-2" onkeypress="if(event.keyCode == 13) return false;" value="{{ $model['topic']['title'] }}" style="height: 25px !important; ">

                            @if ($model['user']['role_id'] > 1 || $model['moder'] || $model['editor'])
                                @if ($model['section_id'] != 5 || $model['forum_id'] == 52)
                                    <p class="forum_comment mb-0">Настройки:</p>
                                    <div class="btn-group col-12 mb-3 new-tema" role="group" aria-label="Basic checkbox toggle button group" style="height: 25px !important">
                                        <input type="checkbox" class="btn-check" name="check[]" id="btncheck1" autocomplete="off" value="pin" @if ($model['topic']['pin'] == 1) checked @endif>
                                        <label class="btn btn-outline-primary p-0 " for="btncheck1"><i class="fa fa-thumb-tack forum-desc me-2"></i><span class="d-sm-inline d-none">
                                                закрепить</span></label>

                                        <input type="checkbox" class="btn-check" name="check[]" id="btncheck2" autocomplete="off" value="block" @if ($model['topic']['block'] == 1) checked @endif>
                                        <label class="btn btn-outline-primary p-0" for="btncheck2"><i class="fa-solid fa-lock forum-desc me-2"></i><span class="d-sm-inline d-none">закрыть</span></label>
                                        @if ($model['section_id'] != 7)
                                            <input type="checkbox" class="btn-check" name="check[]" id="btncheck3" autocomplete="off" value="hide" @if ($model['topic']['hide'] == 1) checked @endif>
                                            <label class="btn btn-outline-primary p-0" for="btncheck3"><i class="fa-regular fa-eye-slash forum-desc me-2"></i><span class="d-sm-inline d-none">скрыть</span></label>

                                            <input type="checkbox" class="btn-check" name="check[]" id="btncheck4" autocomplete="off" value="moder" @if ($model['topic']['moderation'] == 1) checked @endif>
                                            <label class="btn btn-outline-primary p-0" for="btncheck4"><i class="fa-regular fa-hourglass forum-desc me-2"></i><span class="d-sm-inline d-none">модерация</span></label>
                                        @endif
                                    </div>
                                @endif
                            @endif
                            @if ($model['user']['role_id'] > 11 || $model['moder'] || $model['editor'] || $model['user_alliance_moder'] || $model['user_clan_moder'] || $model['user_alliance'] || $model['user_clan'])
                                @if ($model['section_id'] == 5 || $model['forum_id'] != 52)
                                    <p class="forum_comment mb-0">Настройки:</p>
                                    <div class="btn-group col-12 mb-3 new-tema" role="group" aria-label="Basic checkbox toggle button group" style="height: 25px !important">

                                        @if ($model['user_alliance_moder'] || $model['user_clan_moder'])
                                            <input type="checkbox" class="btn-check" name="check[]" id="btncheck1" autocomplete="off" value="pin" @if ($model['topic']['pin'] == 1) checked @endif>
                                            <label class="btn btn-outline-primary p-0 " for="btncheck1"><i class="fa fa-thumb-tack forum-desc me-2"></i><span class="d-sm-inline d-none">
                                                    закрепить</span></label>

                                            <input type="checkbox" class="btn-check" name="check[]" id="btncheck2" autocomplete="off" value="block" @if ($model['topic']['block'] == 1) checked @endif>
                                            <label class="btn btn-outline-primary p-0" for="btncheck2"><i class="fa-solid fa-lock forum-desc me-2"></i><span class="d-sm-inline d-none">закрыть</span></label>

                                            <input type="checkbox" class="btn-check" name="check[]" id="btncheck4" autocomplete="off" value="moder" @if ($model['topic']['moderation'] == 1) checked @endif>
                                            <label class="btn btn-outline-primary p-0" for="btncheck4"><i class="fa-regular fa-hourglass forum-desc me-2"></i><span class="d-sm-inline d-none">модерация</span></label>
                                        @endif
                                        @if ($model['user_alliance_moder'] || $model['user_clan_moder'] || $model['user_alliance'] || $model['user_clan'])
                                            <input type="checkbox" class="btn-check" name="check[]" id="btncheck5" autocomplete="off" value="private" @if ($model['topic']['private'] == 1) checked @endif>
                                            <label class="btn btn-outline-primary px-0" for="btncheck5"><i class="fa-solid fa-chess-rook forum-desc me-2"></i><span class="d-sm-inline d-none">приватная</span></label>
                                        @endif
                                    </div>
                                @endif
                            @endif
                            @if ($model['forum']['hide'] == 1)
                                <p class="small" style="color:#6a0000 !important">* возможно создавать только скрытые
                                    темы
                                </p>
                            @endif
                            @if ($model['forum']['moderation'] == 1)
                                <p class="small" style="color:#6a0000 !important">* премодерация тем на форуме</p>
                            @endif

                            <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                                <a id="topic-edit-hide" class="btn btn-sm btn-dark btn-custom my-0 py-0" style="height: 21px !important">Отмена</a>
                                <input class="btn btn-sm btn-dark btn-custom my-0 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                            </div>
                    </form>
    </div>
@else
    <div class="text-danger">У Вас нет прав для редактирования!</div>
    @endif

    @endif
    @endif
</div>
</div>
