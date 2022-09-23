@php
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
@endphp

@if (!is_null($model['user']))
    <div class="col-12 mt-3 mb-3 pb-3 text-break scroll border-2 border-bottom border-secondary" style="display:none; border-color: #e2d5ac !important;" id="user_ban_inf">
        @if (!is_null($model['user']) && ($model['user']['role_id'] > 1 || $model['other_role_bf']))
            <h5>История блокировок пользователя</h5>
            @if ($model['bans_activ']->count() > 0 || $model['bans_old']->count() > 0)
                <div class="overflow-auto " style="background-color: #f1e9c2;  max-height: 400px;">
                    @if ($model['bans_activ']->count() > 0)
                        <h6 class="fw-bolder">Действующие</h6>
                        @foreach ($model['bans_activ'] as $ban)
                            <div class="d-flex flex-row bd-highlight">
                                <div class="bd-highlight" style="min-width: 85px;">
                                    <span style="color:#4e5256">{{ date('d.m.Y H:i', $ban->datetime) }} </span>
                                </div>
                                <div class="bd-highlight ms-1">
                                    {{-- {{ $ban->user_moder->name }} --}}
                                    @if (!is_null($ban->topic_id))
                                        <div>
                                            <span> в теме </span><a href="{{ url('/t/' . $ban->topic_id) }}"><span class="fw-bolder"> {{ $ban->topic->title }}</span></a>
                                            <span>сроком на {{ $ban->datetime_str }} </span>
                                            @if (ModerHelper::banCancel($model['user'], $ban->id))
                                                <i type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="fa-solid fa-xmark ms-1" style="color: #b50000"></i>
                                            @endif
                                        </div>
                                        <div style="font-size: 9pt;">
                                            <span>выдан </span>
                                            @if ($ban->user_moder->role_id == 2 || $ban->user_moder->role_id == 2)
                                                <span>модератором </span>
                                            @elseif ($ban->user_moder->role_id == 4)
                                                <span>администратором форума </span>
                                            @elseif ($ban->user_moder->role_id >= 5 && $ban->user_moder->role_id <= 8)
                                                <span>сенатором </span>
                                            @elseif ($ban->user_moder->role_id == 9)
                                                <span>зам.главы Сената </span>
                                            @elseif ($ban->user_moder->role_id == 10)
                                                <span>главой Сената </span>
                                            @elseif ($ban->user_moder->role_id > 10)
                                                <span>администратором </span>
                                            @endif
                                            <a href="{{ url('/user/' . $ban->user_moder_id) }}"><span style="{{ ForumHelper::roleStyle($ban->user_moder->role_id) }}"> {{ $ban->user_moder->name }}</span></a>
                                            &ensp; <span style="display: inline-flex;">
                                                <details>
                                                    <summary style="color: #6a0000">ПРИЧИНА</summary>
                                                    <div>{{ $ban->text }}</div>
                                                </details>
                                            </span>
                                        </div>
                                    @elseif (!is_null($ban->forum_id))
                                        <div>
                                            <span> в ветке </span><a href="{{ url('/f/' . $ban->forum_id) }}"><span class="fw-bolder"> {{ $ban->forum->title }}</span></a>
                                            <span>сроком на {{ $ban->datetime_str }} </span>
                                            @if (ModerHelper::banCancel($model['user'], $ban->id))
                                                <i type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="fa-solid fa-xmark ms-1" style="color: #b50000"></i>
                                            @endif
                                        </div>
                                        <div style="font-size: 9pt;">
                                            <span>выдан </span>
                                            @if ($ban->user_moder->role_id == 2 || $ban->user_moder->role_id == 2)
                                                <span>модератором </span>
                                            @elseif ($ban->user_moder->role_id == 4)
                                                <span>администратором форума </span>
                                            @elseif ($ban->user_moder->role_id >= 5 && $ban->user_moder->role_id <= 8)
                                                <span>сенатором </span>
                                            @elseif ($ban->user_moder->role_id == 9)
                                                <span>зам.главы Сената </span>
                                            @elseif ($ban->user_moder->role_id == 10)
                                                <span>главой Сената </span>
                                            @elseif ($ban->user_moder->role_id > 10)
                                                <span>администратором </span>
                                            @endif
                                            <a href="{{ url('/user/' . $ban->user_moder_id) }}"><span style="{{ ForumHelper::roleStyle($ban->user_moder->role_id) }}"> {{ $ban->user_moder->name }}</span></a>
                                            &ensp; <span style="display: inline-flex;">
                                                <details>
                                                    <summary style="color: #6a0000">ПРИЧИНА</summary>
                                                    <div>{{ $ban->text }}</div>
                                                </details>
                                            </span>
                                        </div>
                                    @elseif (!is_null($ban->section_id))
                                        <div>
                                            <span> на форуме </span><a href="{{ url('/f/' . $ban->section_id) }}"><span class="fw-bolder"> {{ $ban->section->title }}</span></a>
                                            <span>сроком на {{ $ban->datetime_str }} </span>
                                            @if (ModerHelper::banCancel($model['user'], $ban->id))
                                                <i type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="fa-solid fa-xmark ms-1" style="color: #b50000"></i>
                                            @endif
                                        </div>
                                        <div style="font-size: 9pt;">
                                            <span>выдан </span>
                                            @if ($ban->user_moder->role_id == 2 || $ban->user_moder->role_id == 2)
                                                <span>модератором </span>
                                            @elseif ($ban->user_moder->role_id == 4)
                                                <span>администратором форума </span>
                                            @elseif ($ban->user_moder->role_id >= 5 && $ban->user_moder->role_id <= 8)
                                                <span>сенатором </span>
                                            @elseif ($ban->user_moder->role_id == 9)
                                                <span>зам.главы Сената </span>
                                            @elseif ($ban->user_moder->role_id == 10)
                                                <span>главой Сената </span>
                                            @elseif ($ban->user_moder->role_id > 10)
                                                <span>администратором </span>
                                            @endif
                                            <a href="{{ url('/user/' . $ban->user_moder_id) }}"><span style="{{ ForumHelper::roleStyle($ban->user_moder->role_id) }}"> {{ $ban->user_moder->name }}</span></a>
                                            &ensp; <span style="display: inline-flex;">
                                                <details>
                                                    <summary style="color: #6a0000">ПРИЧИНА</summary>
                                                    <div>{{ $ban->text }}</div>
                                                </details>
                                            </span>
                                        </div>
                                    @elseif (!is_null($ban->forum_out))
                                        <div>
                                            <span>на </span><a href="{{ url('/main') }}"><span class="fw-bolder">ФОРУМЕ ИГРЫ</span></a>
                                            <span>сроком на {{ $ban->datetime_str }} </span>
                                            @if (ModerHelper::banCancel($model['user'], $ban->id))
                                                <i type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="fa-solid fa-xmark ms-1" style="color: #b50000"></i>
                                            @endif
                                        </div>
                                        <div style="font-size: 9pt;">
                                            <span>выдан </span>
                                            @if ($ban->user_moder->role_id == 2 || $ban->user_moder->role_id == 2)
                                                <span>модератором </span>
                                            @elseif ($ban->user_moder->role_id == 4)
                                                <span>администратором форума </span>
                                            @elseif ($ban->user_moder->role_id >= 5 && $ban->user_moder->role_id <= 8)
                                                <span>сенатором </span>
                                            @elseif ($ban->user_moder->role_id == 9)
                                                <span>зам.главы Сената </span>
                                            @elseif ($ban->user_moder->role_id == 10)
                                                <span>главой Сената </span>
                                            @elseif ($ban->user_moder->role_id > 10)
                                                <span>администратором </span>
                                            @endif
                                            <a href="{{ url('/user/' . $ban->user_moder_id) }}"><span style="{{ ForumHelper::roleStyle($ban->user_moder->role_id) }}"> {{ $ban->user_moder->name }}</span></a>
                                            &ensp; <span style="display: inline-flex;">
                                                <details>
                                                    <summary style="color: #6a0000">ПРИЧИНА</summary>
                                                    <div>{{ $ban->text }}</div>
                                                </details>
                                            </span>
                                        </div>
                                    @endif
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content p-2" style="background: #f1e9c2;">
                                                <form method='post' action='{{ url('/u/b/' . $ban->id . '/cancel') }}'>
                                                    @csrf
                                                    <p class="fw-bold mt-2 mb-1">Причина отмены бана:</p>
                                                    <textarea type="text" name="text" id="text" maxlength="500" title="отмена бана" class="border border-1 input-text mb-2" style="height: 50px !important; "></textarea>

                                                    <div class="row d-flex justify-content-center text_centre my-1">
                                                        <a id="user-ban-hide" data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-dark btn-custom mb-0 py-0 me-2" style="height: 21px !important">Отмена</a>
                                                        <input class="btn btn-sm btn-dark btn-custom ms-2 mb-0 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                        <hr class="my-2 mx-1" style="background: #b59a6b9e;">
                    @endif

                    @if (($model['bans_old']->count() > 0 && !is_null($model['user']) && $model['user']['role_id'] > 1) || $model['other_role_bf'])
                        <h6 class="fw-bolder mt-2 mb-1">Архив</h6>
                        <div class=" overflow-auto " style="max-height: 300px;">
                            @foreach ($model['bans_old'] as $ban)
                                <div class="d-flex flex-row bd-highlight">
                                    <div class="bd-highlight" style="min-width: 85px;">
                                        <span style="color:#4e5256">{{ date('d.m.Y H:i', $ban->datetime) }} </span>
                                    </div>
                                    <div class="bd-highlight ms-1">
                                        {{-- {{ $ban->user_moder->name }} --}}
                                        @if (!is_null($ban->topic_id))
                                            <div>
                                                <span> в теме </span><a href="{{ url('/t/' . $ban->topic_id) }}"><span class="fw-bolder"> {{ $ban->topic->title }}</span></a>
                                                <span>сроком на {{ $ban->datetime_str }} </span>
                                            </div>
                                            <div style="font-size: 9pt;">
                                                <span>выдан </span>
                                                @if ($ban->user_moder->role_id == 2 || $ban->user_moder->role_id == 3)
                                                    <span>модератором </span>
                                                @elseif ($ban->user_moder->role_id == 1)
                                                    <span>пользователем </span>
                                                @elseif ($ban->user_moder->role_id == 4)
                                                    <span>администратором форума </span>
                                                @elseif ($ban->user_moder->role_id >= 5 && $ban->user_moder->role_id <= 8)
                                                    <span>сенатором </span>
                                                @elseif ($ban->user_moder->role_id == 9)
                                                    <span>зам.главы Сената </span>
                                                @elseif ($ban->user_moder->role_id == 10)
                                                    <span>главой Сената </span>
                                                @elseif ($ban->user_moder->role_id > 10)
                                                    <span>администратором </span>
                                                @endif
                                                <a href="{{ url('/user/' . $ban->user_moder_id) }}"><span style="{{ ForumHelper::roleStyle($ban->user_moder->role_id) }}"> {{ $ban->user_moder->name }}</span></a>
                                                &ensp; <span style="display: inline-flex;">
                                                    <details>
                                                        <summary style="color: #6a0000">ПРИЧИНА</summary>
                                                        <div>{{ $ban->text }}</div>
                                                    </details>
                                                </span>
                                            </div>
                                        @elseif (!is_null($ban->forum_id))
                                            <div>
                                                <span> в ветке </span><a href="{{ url('/f/' . $ban->forum_id) }}"><span class="fw-bolder"> {{ $ban->forum->title }}</span></a>
                                                <span>сроком на {{ $ban->datetime_str }} </span>
                                            </div>
                                            <div style="font-size: 9pt;">
                                                <span>выдан </span>
                                                @if ($ban->user_moder->role_id == 2 || $ban->user_moder->role_id == 3)
                                                    <span>модератором </span>
                                                @elseif ($ban->user_moder->role_id == 1)
                                                    <span>пользователем </span>
                                                @elseif ($ban->user_moder->role_id == 4)
                                                    <span>администратором форума </span>
                                                @elseif ($ban->user_moder->role_id >= 5 && $ban->user_moder->role_id <= 8)
                                                    <span>сенатором </span>
                                                @elseif ($ban->user_moder->role_id == 9)
                                                    <span>зам.главы Сената </span>
                                                @elseif ($ban->user_moder->role_id == 10)
                                                    <span>главой Сената </span>
                                                @elseif ($ban->user_moder->role_id > 10)
                                                    <span>администратором </span>
                                                @endif
                                                <a href="{{ url('/user/' . $ban->user_moder_id) }}"><span style="{{ ForumHelper::roleStyle($ban->user_moder->role_id) }}"> {{ $ban->user_moder->name }}</span></a>
                                                &ensp; <span style="display: inline-flex;">
                                                    <details>
                                                        <summary style="color: #6a0000">ПРИЧИНА</summary>
                                                        <div>{{ $ban->text }}</div>
                                                    </details>
                                                </span>
                                            </div>
                                        @elseif (!is_null($ban->section_id))
                                            <div>
                                                <span> на форуме </span><a href="{{ url('/f/' . $ban->section_id) }}"><span class="fw-bolder"> {{ $ban->section->title }}</span></a>
                                                <span>сроком на {{ $ban->datetime_str }} </span>
                                            </div>
                                            <div style="font-size: 9pt;">
                                                <span>выдан </span>
                                                @if ($ban->user_moder->role_id == 2 || $ban->user_moder->role_id == 3)
                                                    <span>модератором </span>
                                                @elseif ($ban->user_moder->role_id == 1)
                                                    <span>пользователем </span>
                                                @elseif ($ban->user_moder->role_id == 4)
                                                    <span>администратором форума </span>
                                                @elseif ($ban->user_moder->role_id >= 5 && $ban->user_moder->role_id <= 8)
                                                    <span>сенатором </span>
                                                @elseif ($ban->user_moder->role_id == 9)
                                                    <span>зам.главы Сената </span>
                                                @elseif ($ban->user_moder->role_id == 10)
                                                    <span>главой Сената </span>
                                                @elseif ($ban->user_moder->role_id > 10)
                                                    <span>администратором </span>
                                                @endif
                                                <a href="{{ url('/user/' . $ban->user_moder_id) }}"><span style="{{ ForumHelper::roleStyle($ban->user_moder->role_id) }}"> {{ $ban->user_moder->name }}</span></a>
                                                &ensp; <span style="display: inline-flex;">
                                                    <details>
                                                        <summary style="color: #6a0000">ПРИЧИНА</summary>
                                                        <div>{{ $ban->text }}</div>
                                                    </details>
                                                </span>
                                            </div>
                                        @elseif (!is_null($ban->forum_out))
                                            <div>
                                                <span>на </span><a href="{{ url('/main') }}"><span class="fw-bolder">ФОРУМЕ ИГРЫ</span></a>
                                                <span>сроком на {{ $ban->datetime_str }} </span>
                                            </div>
                                            <div style="font-size: 9pt;">
                                                <span>выдан </span>
                                                @if ($ban->user_moder->role_id == 2 || $ban->user_moder->role_id == 3)
                                                    <span>модератором </span>
                                                @elseif ($ban->user_moder->role_id == 1)
                                                    <span>пользователем </span>
                                                @elseif ($ban->user_moder->role_id == 4)
                                                    <span>администратором форума </span>
                                                @elseif ($ban->user_moder->role_id >= 5 && $ban->user_moder->role_id <= 8)
                                                    <span>сенатором </span>
                                                @elseif ($ban->user_moder->role_id == 9)
                                                    <span>зам.главы Сената </span>
                                                @elseif ($ban->user_moder->role_id == 10)
                                                    <span>главой Сената </span>
                                                @elseif ($ban->user_moder->role_id > 10)
                                                    <span>администратором </span>
                                                @endif
                                                <a href="{{ url('/user/' . $ban->user_moder_id) }}"><span style="{{ ForumHelper::roleStyle($ban->user_moder->role_id) }}"> {{ $ban->user_moder->name }}</span></a>
                                                &ensp; <span style="display: inline-flex;">
                                                    <details>
                                                        <summary style="color: #6a0000">ПРИЧИНА</summary>
                                                        <div class="text-break">{{ $ban->text }}</div>
                                                    </details>
                                                </span>
                                            </div>
                                        @endif
                                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content p-2" style="background: #f1e9c2;">
                                                    <form method='post' action='{{ url('/b/' . $ban->id . '/del') }}'>
                                                        @csrf
                                                        <p class="fw-bold mt-2 mb-1">Причина отмены бана:</p>
                                                        <textarea type="text" name="text" id="text" maxlength="500" title="отмена бана" class="border border-1 input-text mb-2" style="height: 50px !important; "></textarea>

                                                        <div class="row d-flex justify-content-center text_centre my-1">
                                                            <a id="user-ban-hide" data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-dark btn-custom mb-0 py-0 me-2" style="height: 21px !important">Отмена</a>
                                                            <input class="btn btn-sm btn-dark btn-custom ms-2 mb-0 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- cancel --}}
                                        @if ($ban->cancel)
                                            <div class="fst-italic" style="font-size: 9pt; opacity: 80% !important;">
                                                <span>отмена: {{ date('d.m.Y H:i', $ban->datetime_cancel) }} - </span>
                                                @if ($ban->user_cancel->role_id == 2 || $ban->user_cancel->role_id == 3)
                                                    <span>модератором </span>
                                                @elseif ($ban->user_moder->role_id == 1)
                                                    <span>пользователем </span>
                                                @elseif ($ban->user_cancel->role_id == 4)
                                                    <span>администратором форума </span>
                                                @elseif ($ban->user_cancel->role_id >= 5 && $ban->user_cancel->role_id <= 8)
                                                    <span>сенатором </span>
                                                @elseif ($ban->user_cancel->role_id == 9)
                                                    <span>зам.главы Сената </span>
                                                @elseif ($ban->user_cancel->role_id == 10)
                                                    <span>главой Сената </span>
                                                @elseif ($ban->user_cancel->role_id > 10)
                                                    <span>администратором </span>
                                                @endif
                                                <a href="{{ url('/user/' . $ban->user_cancel_id) }}"><span style="{{ ForumHelper::roleStyle($ban->user_cancel->role_id) }}"> {{ $ban->user_cancel->name }}</span></a>
                                                &ensp; <span style="display: inline-flex;">
                                                    <details>
                                                        <summary>ПРИЧИНА</summary>
                                                        <div class="text-break">{{ $ban->text_cancel }}</div>
                                                    </details>
                                                </span>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <span class="fst-italic">История блокировок пуста</span>
            @endif
        @else
            <span class="d-flex justify-content-center text_centre text-danger">Отсутвуют права для просмотра</span>
        @endif
    </div>
@endif
