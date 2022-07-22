@php
use App\AppForum\Helpers\ForumHelper;
@endphp
@foreach ($model['posts'] as $post)
    <div
        class="row post border border-ligh shadow-sm m-0 text-centre mb-2 @if ($post['moderation']) border border-danger @endif">
        <div class="col-md-3 col-xl-2 col-sm-12 inf text-centre my-1">
            <div class="col-12 my-1">
                <a class="fw-bold text-break" href="{{ url('#') }}">{{ $post['user_post']['name'] }}</a>
            </div>
            <div class="col-12">
                <img style="border-color: #ced4da;" class="logo border bg-white rounded" alt="Cinque Terre"
                    src="https://avatarko.ru/img/avatar/25/igra_Dota_2_Natures_Prophet_24356.jpg">
            </div>
            <div class="col-12 my-1">
                <span class="fw-bold text-black my-2 text-break">Пользователь</span><br>
                <span class="text-muted"><i class="fa-regular fa-message me-1" style="color:rgb(0, 0, 116)"
                        title="Ответы"></i> <span
                        style="color:rgb(0, 0, 116)">{{ $post['user_DATA']->post_count }}</span></span>
                <span class="text-muted"><i class="fa-regular fa-thumbs-up me-1 ms-2" style="color:#0e583d"
                        title="Рейтинг"></i> <span style="color:#0e583d">{{ $post['user_DATA']->like }}</span></span>
                <br>
                <span class="text-muted"><i class="fa-regular fa-envelope me-1" style="color:#2f4f4fe8"></i> <span
                        style="color:#2f4f4fe8">написать</span></span>
            </div>
        </div>
        <div class="col-md-9 col-xl-10 col-sm-12 text-break text"
            @if ($post['hide']) style="background-color: #f7f7e4;" @endif>
            <div class="col-12 text-muted mt-1">
                <div class="row pt-1" style="padding-left: 12px !important;">
                    <div class="col d-flex justify-content-start align-items-center text-center forum-desc">
                        @if ($post['moderation'])
                            <a href="{{ url('/p/' . $post['id'] . '/premod/' . $model['pagination']['page']) }}"><i
                                    class="fa-regular fa-hourglass me-2" style="color: #b80000"
                                    title="Ожидание публикации"></i></a>
                        @endif
                        @if ($post['hide'])
                            <a class="fw-bold" href="{{ url('/p/' . $post['id'] . '/unhide/' . $model['pagination']['page']) }}"><i
                                    class="fa-regular fa-eye-slash me-2" style="color: #5c625e;"
                                    title="Публикация скрыта"></i></a>
                        @endif
                        {{ $post['date'] }}
                        @if (!is_null($post['ip']))
                            &bull; {{ $post['ip'] }}
                        @endif
                    </div>

                    <div class="col-1 d-flex justify-content-end align-items-center">
                        <div class="dropdown">
                            <button id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"
                                @if ($post['hide']) style="border: 0; background: #f7f7e4 !important;" @else style="border: 0; background: #ffffde;" @endif>
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul style="background: #fbf6d1; font-size: small;" class="dropdown-menu"
                                aria-labelledby="dropdownMenuButton1">
                                @if (!is_null($model['user']))
                                    @if ($post['user_id'] == $model['user']['id'] &&
                                        time() <= $post['date_d'] + 3600 &&
                                        is_null($post['DATA']->date_moder))
                                        <li><a class="dropdown-item" style="background: #fbf6d1;"
                                                href="{{ url('/p/' . $post['id'] . '/edit/' . $model['pagination']['page']) }}">
                                                <div class="row">
                                                    <div class="col-1">
                                                        <i class="fa-solid fa-pencil forum-desc ms-1"></i>
                                                    </div>
                                                    <div class="col">
                                                        Редактировать
                                                    </div>
                                                </div>
                                            </a></li>
                                    @endif
                                @endif
                                <li><a class="dropdown-item" style="background: #fbf6d1;" href="#">
                                        <div class="row">
                                            <div class="col-1">
                                                <i class="fa-solid fa-triangle-exclamation forum-desc ms-1"></i>
                                            </div>
                                            <div class="col">
                                                Пожаловаться
                                            </div>
                                        </div>
                                    </a></li>
                                <li><a class="dropdown-item" style="background: #fbf6d1;"
                                        href="{{ url('/p/' . $post['id'] . '/moder/' . $model['pagination']['page']) }}">
                                        <div class="row">
                                            <div class="col-1">
                                                <i class="fa-regular fa-sun forum-desc ms-1"></i>
                                            </div>
                                            <div class="col">
                                                Модерация
                                            </div>
                                        </div>
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 my-3"><?php
            echo htmlspecialchars_decode($post['text']);
            ?>
            </div>
            <div class="col-12 py-4" style="color:#700000 !important">
                <i class="fa-solid fa-user-lock forum-desc" style="color:#700000 !important" title="Доступ закрыт"></i>
                <br>
                BETAJIb
            </div>
            {{-- @dd($post['DATA']) --}}
            <div class="row forum-desc">
                @if (!is_null($post['DATA']->user_name_edit) &&
                    !is_null($post['DATA']->date_edit) &&
                    !is_null($post['DATA']->first_edit) &&
                    is_null($post['DATA']->date_moder))
                    <div class="col fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                        <i class="fa-solid fa-pencil me-1"></i> &nbsp; <span
                            class="fw-bold">{{ $post['DATA']->user_name_edit }} &middot; &nbsp;</span>
                        {{ ForumHelper::timeFormat($post['DATA']->date_edit) }}
                    </div>
                    <div class="row forum-desc">
                        <div class="col fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                            <details>
                                <summary>Исходный пост:</summary>
                                <p><?php
                                echo htmlspecialchars_decode($post['DATA']->first_edit);
                                ?></p>
                            </details>
                        </div>
                    </div>
                    {{-- <div class="row forum-desc">
                        <div class="col ms-4 fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                            <?php
                            echo htmlspecialchars_decode($post['DATA']->first);
                            ?>
                        </div>
                    </div> --}}
                @endif
                @if (!is_null($post['DATA']->user_name_moder) &&
                    !is_null($post['DATA']->date_moder) &&
                    !is_null($post['DATA']->first))
                    <div class="col fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                        <i class="fa-solid fa-pencil me-1" style="color:#700000"></i> &nbsp; <span class="fw-bold"
                            style="color:#700000">{{ $post['DATA']->user_name_moder }} &middot; &nbsp;</span>
                        {{ ForumHelper::timeFormat($post['DATA']->date_moder) }}
                    </div>
                    <div class="row forum-desc">
                        <div class="col fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                            <details>
                                <summary>Исходный пост:</summary>
                                <p><?php
                                echo htmlspecialchars_decode($post['DATA']->first);
                                ?></p>
                            </details>
                        </div>
                    </div>
                    {{-- <div class="row forum-desc">
                        <div class="col ms-4 fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                            <?php
                            echo htmlspecialchars_decode($post['DATA']->first);
                            ?>
                        </div>
                    </div> --}}
                @endif
                <div class="col p-0 d-flex justify-content-end align-items-center text-center">
                    <i class="fa-solid fa-share me-1"></i> Ответить
                </div>
            </div>
            {{-- @dd($post['DATA']) --}}
            <div class="col-12 forum-desc fs-6 pb-3 text-break">
                <hr class="mt-0">
                Подпись ⋙ 👍
                <div class="col-12 mt-4">
                    <div class="row">
                        <div class="col d-flex justify-content-start align-items-center text-center ">
                            <i class="fa-regular fa-comment-dots me-1" style="color:rgb(0, 0, 116)"></i> <span
                                style="color:rgb(0, 0, 116)">0</span>
                        </div>
                        <div class="col d-flex justify-content-end align-items-center text-center">
                            @include('topic.inc.like', ['model' => $model])

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
