@foreach ($model['posts'] as $post)
    <div
        class="row post border border-ligh shadow-sm m-0 text-centre mb-2 @if ($post['moderation']) border border-danger @endif">
        <div class="col-md-3 col-xl-2 col-sm-12 inf text-centre my-1">
            <div class="col-12 my-1">
                <a class="fw-bold" href="{{ url('#') }}">{{ $post['user_post']['name'] }}</a>
            </div>
            <div class="col-12">
                <img style="border-color: #ced4da;" class="logo border bg-white rounded" alt="Cinque Terre"
                    src="https://avatarko.ru/img/avatar/25/igra_Dota_2_Natures_Prophet_24356.jpg">
            </div>
            <div class="col-12 my-1">
                <span class="fw-bold text-black my-2">Пользователь</span><br>
                <span class="text-muted"><i class="fa-regular fa-message me-1" style="color:rgb(0, 0, 116)"
                        title="Ответы"></i> <span style="color:rgb(0, 0, 116)">10</span></span>
                <span class="text-muted"><i class="fa-regular fa-thumbs-up me-1 ms-2" style="color:DarkGreen"
                        title="Рейтинг"></i> <span style="color:DarkGreen">1</span></span> <br>
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
                            <a href="{{ url('p/' . $post['id'] . '/premod') }}"><i
                                    class="fa-regular fa-hourglass me-2" style="color: #b80000"
                                    title="Ожидание публикации"></i></a>
                        @endif
                        @if ($post['hide'])
                            <a class="fw-bold" href="{{ url('p/' . $post['id'] . '/unhide') }}"><i
                                    class="fa-regular fa-eye-slash me-2" style="color: #5c625e;"
                                    title="Публикация скрыта"></i></a>
                        @endif
                        {{ $post['date'] }}
                    </div>
                    <div class="col-1 d-flex justify-content-end align-items-center">
                        <div class="dropdown">
                            <button id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"
                                @if ($post['hide']) style="border: 0; background: #f7f7e4 !important;" @else style="border: 0; background: #ffffde;" @endif>
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul style="background: #fbf6d1; font-size: small;" class="dropdown-menu"
                                aria-labelledby="dropdownMenuButton1">
                                @if ($post['user_id'] == $model['user']['id'] && time() <= strtotime($post['date']) + 3600)
                                    <li><a class="dropdown-item" style="background: #fbf6d1;"
                                            href={{ url('/p/' . $post['id'] . '/edit') }}>
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
                                <li><a class="dropdown-item" style="background: #fbf6d1;" href="#">
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
            <div class="col-12 py-4" style="color:#660000 !important">
                <i class="fa-solid fa-user-lock forum-desc" style="color:#660000 !important" title="Доступ закрыт"></i>
                <br>
                BETAJIb
            </div>
            <div class="row forum-desc">
                <div class="col  p-0 d-flex justify-content-start align-items-center text-center">
                    <i class="fa-solid fa-pencil me-1"></i> 03.06.2022 14:52
                </div>
                <div class="col p-0 d-flex justify-content-end align-items-center text-center">
                    <i class="fa-solid fa-share me-1"></i> Ответить
                </div>
            </div>
            <div class="col-12 forum-desc fs-6 pb-3">
                <hr class="mt-0">
                Подпись ⋙ 👍
                <div class="col-12 mt-4">
                    <div class="row">
                        <div class="col d-flex justify-content-start align-items-center text-center">
                            <i class="fa-regular fa-comment-dots me-1" style="color:rgb(0, 0, 116)"></i> <span
                                style="color:rgb(0, 0, 116)">0</span>
                        </div>
                        <div class="col d-flex justify-content-end align-items-center text-center">
                            <i class="fa-regular fa-thumbs-up me-1" style="color:DarkGreen"></i> <span
                                style="color:DarkGreen">0</span>
                            <i class="fa-regular fa-thumbs-down me-1 ms-2" style="color: #660000"></i> <span
                                style="color: #660000">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
