@if (!is_null($model['user']))
    <div class="col-12 mt-3 text-break scroll" style="background: rgb(246, 240, 204); display:none;" id="user_ban">
        <div id="carouselExampleDark" data-bs-touch="true" class="carousel carousel-dark slide mx-0" data-bs-interval="false" data-bs-ride="carousel">
            <div class="carousel-indicators mb-1">
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 3"></button>

                @if ((!is_null($model['user']) && $model['user']['role_id'] > 8) || $model['user']['role_id'] == 4)
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 2"></button>
                @endif

                @if (!is_null($model['user']) && $model['user']['role_id'] > 10)
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3" aria-label="Slide 4"></button>
                @endif
            </div>
            <div class="carousel-inner pb-4 px-5 pt-3 overflow-auto">
                <div class="carousel-item active pb-2 text-break">
                    @if ((!is_null($model['forums_block']) && $model['forums_block']->count() != 0 && $model['user']['role_id'] >= $model['user_inf']['role_id']) || $model['other_role_bf'])
                        <h5>Блокировка пользователей</h5>
                        <form method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/topic_ban') }}'>
                            @csrf
                            <div class="row">
                                <div class="col-12 my-1" style="min-height:218px !important">
                                    <p class="fw-bold">Темы форумов</p>
                                    <div class="col-auto d-flex justify-content-start pe-0"><span>Id темы:&nbsp;</span><input type="number" name="id" id="only_num" title="дней бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                    <p class="fw-bold">Причина бана:</p>
                                    <textarea type="text" name="text" id="text" maxlength="500" title="причина бана" class="border border-1 input-text mb-2" style="height: 29px !important; "></textarea>
                                    <p class="fw-bold">Время:</p>
                                    <div class="row">
                                        <div class="col-auto d-flex justify-content-start pe-0"><span>дни:&nbsp;</span><input type="number" name="d" id="only_num" title="дней бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                        <div class="col-auto d-flex justify-content-start pe-0"><span>часы:&nbsp;</span><input type="number" name="h" id="only_num" title="часов бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                        <div class="col-auto d-flex justify-content-start pe-0"><span>минуты:&nbsp;</span><input type="number" name="m" id="only_num" title="минут бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                    </div>
                                    @if ($model['user']['role_id'] == 2 || $model['user']['role_id'] == 5)
                                        <div class="forum-desc" style="color: #6a0000 !important;">*время бана не более 24ч (1 дня)</div>
                                    @endif
                                </div>
                                <div class="row d-flex justify-content-center text_centre mt-2">
                                    <a onclick="userBanHide()" data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-dark btn-custom mt-2 mb-0 py-0 me-2" style="height: 21px !important">Отмена</a>
                                    <input class="btn btn-sm btn-dark btn-custom ms-2 mt-2 mb-0 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                                </div>
                            </div>
                        </form>
                    @else
                        <span class="d-flex justify-content-center text_centre text-danger">Отсутвуют права для блокировки!</span>
                    @endif

                </div>
                <div class="carousel-item pb-2 text-break">
                    @if ((!is_null($model['forums_block']) && $model['forums_block']->count() != 0 && $model['user']['role_id'] >= $model['user_inf']['role_id']) || ($model['forums_block']->count() != 0 && $model['other_role_bf']))
                        <h5>Блокировка пользователей</h5>
                        <form method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/forum_ban') }}'>
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-md-5 col-12 my-1" style="min-height:218px !important">
                                    <p class="fw-bold">Ветки форумов</p>
                                    <div class="overflow-auto border-bottom border-top" style="max-height: 150px; position: relative; border-color: #e3dbb7 !important;">
                                        @foreach ($model['forums_block'] as $forum)
                                            <div class="form-check p-0 new-tema">
                                                @if ($forum->section_id == 7)
                                                    <input type="radio" class="btn-check" name="check[]" id="f{{ $forum['id'] }}" value="{{ $forum['id'] }}">
                                                    <label class="btn btn-outline-primary p-0" style="color: #bd0000" for="f{{ $forum['id'] }}">
                                                        <span class="d-sm-inline">{{ $forum['title'] }}</span>
                                                    </label>
                                                @elseif ($forum->id == 16 || $forum->id == 17 || $forum->id == 39 || $forum->id == 40 || $forum->section_id == 5)
                                                    <input type="radio" class="btn-check" name="check[]" id="f{{ $forum['id'] }}" value="{{ $forum['id'] }}">
                                                    <label class="btn btn-outline-primary p-0" style="color: #00299d" for="f{{ $forum['id'] }}">
                                                        <span class="d-sm-inline">{{ $forum['title'] }}</span>
                                                    </label>
                                                @else
                                                    <input type="radio" class="btn-check" name="check[]" id="f{{ $forum['id'] }}" value="{{ $forum['id'] }}">
                                                    <label class="btn btn-outline-primary p-0" style="color: #160f0a" for="f{{ $forum['id'] }}">
                                                        <span class="d-sm-inline">{{ $forum['title'] }}</span>
                                                    </label>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-7 col-12  my-1" style="min-height:218px !important">
                                    <p class="fw-bold">Причина бана:</p>
                                    <textarea type="text" name="text" id="text" maxlength="500" title="причина бана" class="border border-1 input-text mb-2" style="height: 50px !important; "></textarea>
                                    <p class="fw-bold">Время:</p>
                                    <div class="row">
                                        <div class="col-auto d-flex justify-content-start pe-0"><span>дни:&nbsp;</span><input type="number" name="d" id="only_num" title="дней бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                        <div class="col-auto d-flex justify-content-start pe-0"><span>часы:&nbsp;</span><input type="number" name="h" id="only_num" title="часов бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                        <div class="col-auto d-flex justify-content-start pe-0"><span>минуты:&nbsp;</span><input type="number" name="m" id="only_num" title="минут бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                    </div>
                                    @if ($model['user']['role_id'] == 2 || $model['user']['role_id'] == 5)
                                        <div class="forum-desc" style="color: #6a0000 !important;">*время бана не более 24ч (1 дня)</div>
                                    @endif
                                </div>
                                <div class="row d-flex justify-content-center text_centre mt-2">
                                    <a id="user-ban-hide" data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-dark btn-custom mt-2 mb-0 py-0 me-2" style="height: 21px !important">Отмена</a>
                                    <input class="btn btn-sm btn-dark btn-custom ms-2 mt-2 mb-0 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                                </div>
                            </div>
                        </form>
                    @else
                        <span class="d-flex justify-content-center text_centre text-danger">Отсутвуют права для блокировки!</span>
                    @endif
                </div>
                @if ((!is_null($model['user']) && $model['user']['role_id'] > 8) || $model['user']['role_id'] == 4)
                    <div class="carousel-item pb-2 text-break">
                        @if (!is_null($model['sections_block']) && $model['sections_block']->count() != 0 && $model['user']['role_id'] >= $model['user_inf']['role_id'])
                            <h5>Блокировка пользователей</h5>
                            <form method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/section_ban') }}'>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-5 col-12 scroll my-1" style="min-height:218px !important">
                                        <p class="fw-bold">Форумы</p>
                                        <div class="overflow-auto border-bottom border-top" style="max-height: 150px; position: relative; border-color: #e3dbb7 !important;">
                                            @foreach ($model['sections_block'] as $section)
                                                <div class="form-check p-0 new-tema">
                                                    @if ($section->id == 7)
                                                        <input type="radio" class="btn-check" name="check1[]" id="s{{ $section['id'] }}" value="{{ $section['id'] }}">
                                                        <label class="btn btn-outline-primary p-0" style="color: #bd0000" for="s{{ $section['id'] }}">
                                                            <span class="d-sm-inline">{{ $section['title'] }}</span>
                                                        </label>
                                                    @elseif ($section->id == 5)
                                                        <input type="radio" class="btn-check" name="check1[]" id="s{{ $section['id'] }}" value="{{ $section['id'] }}">
                                                        <label class="btn btn-outline-primary p-0" style="color: #00299d" for="s{{ $section['id'] }}">
                                                            <span class="d-sm-inline">{{ $section['title'] }}</span>
                                                        </label>
                                                    @else
                                                        <input type="radio" class="btn-check" name="check1[]" id="s{{ $section['id'] }}" value="{{ $section['id'] }}">
                                                        <label class="btn btn-outline-primary p-0" style="color: #160f0a" for="s{{ $section['id'] }}">
                                                            <span class="d-sm-inline">{{ $section['title'] }}</span>
                                                        </label>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-7 col-12  my-1" style="min-height:218px !important">
                                        <p class="fw-bold">Причина бана:</p>
                                        <textarea type="text" name="text" id="text" maxlength="500" title="причина бана" class="border border-1 input-text mb-2" style="height: 50px !important; "></textarea>
                                        <p class="fw-bold">Время:</p>
                                        <div class="row">
                                            <div class="col-auto d-flex justify-content-start pe-0"><span>дни:&nbsp;</span><input type="number" name="d" id="only_num" title="дней бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                            <div class="col-auto d-flex justify-content-start pe-0"><span>часы:&nbsp;</span><input type="number" name="h" id="only_num" title="часов бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                            <div class="col-auto d-flex justify-content-start pe-0"><span>минуты:&nbsp;</span><input type="number" name="m" id="only_num" title="минут бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                        </div>
                                        @if ($model['user']['role_id'] == 2 || $model['user']['role_id'] == 5)
                                            <div class="forum-desc" style="color: #6a0000 !important;">*время бана не более 24ч (1 дня)</div>
                                        @endif
                                    </div>
                                    <div class="row d-flex justify-content-center text_centre mt-2">
                                        <a id="user-ban-hide2" data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-dark btn-custom mt-2 mb-0 py-0 me-2" style="height: 21px !important">Отмена</a>
                                        <input class="btn btn-sm btn-dark btn-custom ms-2 mt-2 mb-0 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                                    </div>
                                </div>
                            </form>
                        @else
                            <span class="d-flex justify-content-center text_centre text-danger">Отсутвуют права для блокировки!</span>
                        @endif
                    </div>
                @endif
                @if (!is_null($model['user']) && $model['user']['role_id'] > 10)
                    <div class="carousel-item pb-2 text-break">
                        @if (!is_null($model['user']) && $model['user']['role_id'] > 10 && $model['user']['role_id'] >= $model['user_inf']['role_id'])
                            <h5>Блокировка пользователей</h5>
                            <form method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/forum_out') }}'>
                                @csrf
                                <div class="row">
                                    <div class="col-12 my-1" style="min-height:218px !important">
                                        <h5 class="fw-bold my-3"style="color: #c50000">ДОСТУП НА ФОРУМ</h5>
                                        <p class="fw-bold">Причина бана:</p>
                                        <textarea type="text" name="text" id="text" maxlength="500" title="причина бана" class="border border-1 input-text mb-2" style="height: 29px !important; "></textarea>
                                        <p class="fw-bold">Время:</p>
                                        <div class="row">
                                            <div class="col-auto d-flex justify-content-start pe-0"><span>дни:&nbsp;</span><input type="number" name="d" id="only_num" title="дней бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                            <div class="col-auto d-flex justify-content-start pe-0"><span>часы:&nbsp;</span><input type="number" name="h" id="only_num" title="часов бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                            <div class="col-auto d-flex justify-content-start pe-0"><span>минуты:&nbsp;</span><input type="number" name="m" id="only_num" title="минут бана" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;"></div>
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-center text_centre mt-2">
                                        <a id="user-ban-hide3" data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-dark btn-custom mt-2 mb-0 py-0 me-2" style="height: 21px !important">Отмена</a>
                                        <input class="btn btn-sm btn-dark btn-custom ms-2 mt-2 mb-0 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                                    </div>
                                </div>
                            </form>
                        @else
                            <span class="d-flex justify-content-center text_centre text-danger">Отсутвуют права для блокировки!</span>
                        @endif
                    </div>
                @endif
            </div>

            <button class="carousel-control-prev d-flex justify-content-start" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev" style="max-width: 13px;">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">назад</span>
            </button>
            <button class="carousel-control-next d-flex justify-content-end" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next" style="max-width: 13px;">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">дальше</span>
            </button>
        </div>
    </div>
@endif
<script>
    $(document).ready(function() {
        $("#only_num").keydown(function(event) {
            // Разрешаем: backspace, delete, tab и escape
            if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
                // Разрешаем: Ctrl+A
                (event.keyCode == 65 && event.ctrlKey === true) ||
                // Разрешаем: home, end, влево, вправо
                (event.keyCode >= 35 && event.keyCode <= 39)) {

                // Ничего не делаем
                return;
            } else {
                // Запрещаем все, кроме цифр на основной клавиатуре, а так же Num-клавиатуре
                if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                    event.preventDefault();
                }
            }
        });
    });
</script>
