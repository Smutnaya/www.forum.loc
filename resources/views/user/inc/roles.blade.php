@if (!is_null($model['user']))
    @if ($model['user']['role_id'] > 10 && $model['user_inf']['role_id'] <= $model['user']['role_id'])
        <div class="col-12 p-2 pb-4 mt-3 text-break" style="background: rgb(246, 240, 204); display:none;" id="roles">
            <div class="row m-0">
                <div class="col-12 col-md-4 text-center border roles-button shadow-sm mb-1" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal4">Доступ на форум</div>
                <div class="col-12 col-md-4 text-center border roles-button shadow-sm mb-1" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal5">Доступ на ветку</div>
                <div class="col-12 col-md-4 text-center border roles-button shadow-sm mb-1" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal3">Доступ на тему</div>
            </div>
            @if ($model['user_other_role']->count() > 0)
                <div class="col-12 fs-5 mt-2">Доступы пользователя</div>
                <div class="row m-0 border-top border-bottom forum-desc" style="border-color: #e2d5ac !important; background-color: #eae1bc;">
                    <div class="col-2 text-center"></div>
                    <div class="col text-center">заголовок</div>
                    <div class="col-1 text-center">модер</div>
                    <div class="col-1 text-center"></div>
                </div>
                @foreach ($model['user_other_role'] as $role)
                    <div class="row m-0 border-bottom" style="border-color: #e2d5ac !important;">
                        @if (!is_null($role['topic_id']))
                            <div class="col-2 text-center forum-desc">тема</div>
                        @endif
                        @if (!is_null($role['forum_id']))
                            <div class="col-2 text-center forum-desc">ветка</div>
                        @endif
                        @if (!is_null($role['section_id']))
                            <div class="col-2 text-center forum-desc">форум</div>
                        @endif
                        <div class="col text-center">{{ $role['title'] }}</div>
                        <div class="col-1 text-center">
                            @if ($role['moderation'])
                                <form class="d-flex justify-content-center" method='post' action='{{ url('/u/r/' . $role['id'] . '/moder_false') }}'>
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0 form-check-input" style="background-color: transparent !important; border-color: transparent !important;">
                                        <i class="fa-solid fa-plus" style="color: #006843;"></i>
                                    </button>
                                </form>
                            @endif
                            @if (!$role['moderation'])
                                <form class="d-flex justify-content-center" method='post' action='{{ url('/u/r/' . $role['id'] . '/moder_true') }}'>
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0 form-check-input" style="background-color: transparent !important; border-color: transparent !important;">
                                        <i class="fa-solid fa-minus" style="color: #c50000;"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="col-1 text-center">
                            <form class="d-flex justify-content-center" method='post' action='{{ url('/u/r/' . $role['id'] . '/del') }}'>
                                @csrf
                                <button type="submit" class="btn btn-sm p-0 form-check-input" style="background-color: transparent !important; border-color: transparent !important;">
                                    <i class="fa-solid fa-xmark" style="color: #555346;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 pt-4 text-center">Доступы отсутсвуют</div>
            @endif

            <div class="modal fade scroll" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered p-2" style="max-width: 380px">
                    <div class="modal-content p-2" style="background: #f1e9c2;">
                        <form method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/role_section') }}'>
                            @csrf
                            <div class="row m-0">
                                <div class="col-11 px-0">
                                    <p class="fw-bold mb-2">Доступ на форум</p>
                                </div>
                                <div class="col-1 d-flex justify-content-end pt-1 px-0">
                                    <button type="button" class="btn-close p-0" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                            </div>
                            <hr class="mt-0 mb-2 hr-color shadow-sm">
                            <div class="d-flex justify-content-end pb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="moder" value="true" id="checkboxModer" dischecked>
                                    <label class="form-check-label" for="checkboxModer" style="color: #6a0000 !important">
                                        модерация
                                    </label>
                                </div>
                            </div>
                            <div class="overflow-auto border-bottom border-top" style="max-height: 150px; position: relative; border-color: #e3dbb7 !important;">
                                @foreach ($model['sections_block'] as $section)
                                    <div class="form-check p-0 new-tema">
                                        @if ($section->id == 7)
                                            <input type="radio" class="btn-check" name="check1[]" id="s{{ $section['id'] }}" value="{{ $section['id'] }}">
                                            <label class="btn btn-outline-primary p-0" style="color: #bd0000 !important" for="s{{ $section['id'] }}">
                                                <span class="d-sm-inline">{{ $section['title'] }}</span>
                                            </label>
                                        @elseif ($section->id == 5)
                                            <input type="radio" class="btn-check" name="check1[]" id="s{{ $section['id'] }}" value="{{ $section['id'] }}">
                                            <label class="btn btn-outline-primary p-0" style="color: #00299d !important" for="s{{ $section['id'] }}">
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
                            <div class="d-flex justify-content-center">
                                <input class="btn btn-sm btn-dark btn-custom ms-2 my-1 mt-3 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade scroll" id="exampleModal5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered p-2" style="max-width: 380px">
                    <div class="modal-content p-2" style="background: #f1e9c2;">
                        <form method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/role_forum') }}'>
                            @csrf
                            <div class="row m-0">
                                <div class="col-11 px-0">
                                    <p class="fw-bold mb-2">Доступ на ветку форума</p>
                                </div>
                                <div class="col-1 d-flex justify-content-end pt-1 px-0">
                                    <button type="button" class="btn-close p-0" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                            </div>
                            <hr class="mt-0 mb-2 hr-color shadow-sm">
                            <div class="d-flex justify-content-end pb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="moder" value="true" id="flexCheckDefault1" dischecked>
                                    <label class="form-check-label" for="flexCheckDefault1" style="color: #6a0000 !important">
                                        модерация
                                    </label>
                                </div>
                            </div>
                            <div class="overflow-auto border-bottom border-top" style="max-height: 150px; position: relative; border-color: #e3dbb7 !important;">
                                @foreach ($model['forums_block'] as $forum)
                                    <div class="form-check p-0 new-tema">
                                        @if ($forum->section_id == 7)
                                            <input type="radio" class="btn-check" name="check2[]" id="f{{ $forum['id'] }}" value="{{ $forum['id'] }}">
                                            <label class="btn btn-outline-primary p-0" style="color: #bd0000" for="f{{ $forum['id'] }}">
                                                <span class="d-sm-inline">{{ $forum['title'] }}</span>
                                            </label>
                                        @elseif ($forum->id == 16 || $forum->id == 17 || $forum->id == 39 || $forum->id == 40 || $forum->section_id == 5)
                                            <input type="radio" class="btn-check" name="check2[]" id="f{{ $forum['id'] }}" value="{{ $forum['id'] }}">
                                            <label class="btn btn-outline-primary p-0" style="color: #00299d" for="f{{ $forum['id'] }}">
                                                <span class="d-sm-inline">{{ $forum['title'] }}</span>
                                            </label>
                                        @else
                                            <input type="radio" class="btn-check" name="check2[]" id="f{{ $forum['id'] }}" value="{{ $forum['id'] }}">
                                            <label class="btn btn-outline-primary p-0" style="color: #160f0a" for="f{{ $forum['id'] }}">
                                                <span class="d-sm-inline">{{ $forum['title'] }}</span>
                                            </label>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-center">
                                <input class="btn btn-sm btn-dark btn-custom ms-2 my-1 mt-3 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade scroll" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered p-2" style="max-width: 380px">
                    <div class="modal-content p-2" style="background: #f1e9c2;">
                        <form method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/role_topic') }}'>
                            @csrf
                            <div class="row m-0">
                                <div class="col-11 px-0">
                                    <p class="fw-bold mb-2">Доступ на тему</p>
                                </div>
                                <div class="col-1 d-flex justify-content-end pt-1 px-0">
                                    <button type="button" class="btn-close p-0" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                            </div>
                            <hr class="mt-0 mb-2 hr-color shadow-sm">
                            <div class="row m-1 py-2">
                                <div class="col-6 d-flex justify-content-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="moder" value="true" id="flexCheckDefault2" dischecked>
                                        <label class="form-check-label" for="flexCheckDefault2" style="color: #6a0000 !important">
                                            модерация
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 d-flex justify-content-center">
                                    <span>Id темы:&nbsp;</span><input type="number" name="id" id="only_num" style="height: 22px" class="border border-1 input-text-number mb-2 d-inline" onkeypress="if(event.keyCode == 13) return false;">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <input class="btn btn-sm btn-dark btn-custom ms-2 my-1 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endif
@endif
