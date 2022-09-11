@php
use App\AppForum\Helpers\ForumHelper;
@endphp

@foreach ($model['posts'] as $post)
    <div class="row post border border-ligh shadow-sm m-0 text-centre mb-2 overflow-hidden @if ($post['moderation']) border border-danger @endif">
        <div class="col-md-3 col-xl-2 col-sm-12 inf text-centre my-1">
            <div class="col-12 my-1">
                <a class="fw-bold text-break" href="{{ url('/user/' . $post['user_post']['id']) }}">{{ $post['user_post']['name'] }}</a>
            </div>
            <div class="col-12">
                <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="logo rounded" alt="Cinque Terre" @if (!is_null($post['avatar']))
                src="/storage{{ $post['avatar'] }}"
                @else
                src="/images/av.png"
                @endif>
            </div>
            <div class="col-12 my-1">
                <span class="fw-bold text-black my-2 text-break" style="font-size: 10pt; {{ $post['user_role_style'] }}">{{ $post['user_role'] }}</span><br>
                <span class="text-muted"><i class="fa-regular fa-message me-1" style="color:rgb(0, 0, 116)" title="Ответы"></i> <span style="color:rgb(0, 0, 116)">{{ $post['user_DATA']->post_count }}</span></span>
                @if ($post['user_DATA']->like < 0)
                    <span class="text-muted"><i class="fa-regular fa-thumbs-down me-1 ms-2" style="color: #6a0000" title="Рейтинг"></i> <span style="color:#6a0000">{{ $post['user_DATA']->like }}</span></span>
                @else
                    <span class="text-muted"><i class="fa-regular fa-thumbs-up me-1 ms-2" style="color:#0e583d" title="Рейтинг"></i> <span style="color:#0e583d">{{ $post['user_DATA']->like }}</span></span>
                @endif
                <br>
                <span class="text-muted"><i class="fa-regular fa-envelope me-1" style="color:#2f4f4fe8"></i> <span style="color:#2f4f4fe8">написать</span></span>
            </div>
        </div>
        <div class="col-md-9 col-xl-10 col-sm-12 text-break text" @if ($post['hide']) style="background-color: #f7f7e4;" @endif>
            <div class="col-12 text-muted">
                <div class="row pt-1" style="padding-left: 12px !important;">
                    <div class="col d-flex justify-content-start align-items-center text-center forum-desc">
                        @if ($post['moderation'])
                            <a @if ($post['postModer']) href="{{ url('/p/' . $post['id'] . '/premod/' . $model['pagination']['page']) }}" @endif><i class="fa-regular fa-hourglass me-2" style="color: #b80000" title="Ожидание публикации"></i></a>
                        @endif
                        @if ($post['hide'])
                            <a class="fw-bold" @if ($post['postModer']) href="{{ url('/p/' . $post['id'] . '/unhide/' . $model['pagination']['page']) }}" @endif><i class="fa-regular fa-eye-slash me-2" style="color: #5c625e;" title="Публикация скрыта"></i></a>
                        @endif
                        <span>{{ $post['date'] }}</span>
                        @if (!is_null($post['ip'] && $post['postModer']))
                            &nbsp; &bull; {{ $post['ip'] }}
                        @endif

                    </div>
                    <div class="col-1 d-flex justify-content-end align-items-center">
                        <div class="dropdown">
                            <button id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" @if ($post['hide']) style="border: 0; background: #f7f7e4 !important;" @else style="border: 0; background: #ffffde;" @endif>
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul style="background: #fbf6d1; font-size: small;" class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                @if (!is_null($model['user']))
                                    @if ($post['postEdit'] || $post['postModer'])
                                        <li><a class="dropdown-item" style="background: #fbf6d1;" href="{{ url('/p/' . $post['id'] . '/edit/' . $model['pagination']['page']) }}">
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
                                @if ($post['postModer'])
                                    <li><a class="dropdown-item" style="background: #fbf6d1;" href="{{ url('/p/' . $post['id'] . '/moder/' . $model['pagination']['page']) }}">
                                            <div class="row">
                                                <div class="col-1">
                                                    <i class="fa-regular fa-sun forum-desc ms-1"></i>
                                                </div>
                                                <div class="col">
                                                    Модерация
                                                </div>
                                            </div>
                                        </a></li>
                                @endif
                                @if (!is_null($model['user']) && $model['user']['role_id'] >= 11 && $model['first_post'] != $post['id'])
                                    <li><a class="dropdown-item" style="background: #fbf6d1;" href="{{ url('/p/' . $post['id'] . '/del/' . $model['pagination']['page']) }}">
                                            <div class="row">
                                                <div class="col-1">
                                                    <i class="fa-solid fa-circle-minus forum-desc ms-1"></i>
                                                </div>
                                                <div class="col">
                                                    Удалить ответ
                                                </div>
                                            </div>
                                        </a></li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 my-1"><?php
            echo htmlspecialchars_decode($post['text']);
            ?>
            </div>
            {{-- <div class="col-12 my-1" style="color:#6a0000 !important">
                <i class="fa-solid fa-user-lock forum-desc" style="color:#6a0000 !important" title="Доступ закрыт"></i>
                <br>
                BETAJIb
            </div> --}}
            <div class="col-12">
                <div class="row mt-4 mx-0">
                    @if (!is_null($post['DATA']->user_name_edit) && !is_null($post['DATA']->date_edit) && !is_null($post['DATA']->first_edit) && is_null($post['DATA']->date_moder))
                        <div class="col forum-desc fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                            <i class="fa-solid fa-pencil me-1"></i> &nbsp; <span class="fw-bold">{{ $post['DATA']->user_name_edit }} &middot; &nbsp;</span>
                            {{ ForumHelper::timeFormat($post['DATA']->date_edit) }}
                        </div>
                        @if ($post['postModer'])
                            <div class="row m-0 p-0">
                                <div class="col forum-desc fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                                    <details>
                                        <summary>Исходный пост:</summary>
                                        <p><?php
                                        echo htmlspecialchars_decode($post['DATA']->first_edit);
                                        ?></p>
                                    </details>
                                </div>
                                @include('topic.inc.reply', ['post' => $post])
                            </div>
                        @else
                            @include('topic.inc.reply', ['post' => $post])
                        @endif
                    @elseif (!is_null($post['DATA']->user_name_moder) && !is_null($post['DATA']->date_moder) && !is_null($post['DATA']->first))
                        <div class="col forum-desc fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                            <i class="fa-solid fa-pencil me-1" style="color:#6a0000"></i> &nbsp; <span class="fw-bold" style="color:#6a0000">{{ $post['DATA']->user_name_moder }} &middot; &nbsp;</span>
                            {{ ForumHelper::timeFormat($post['DATA']->date_moder) }}
                        </div>
                        @if ($post['postModer'])
                            <div class="row  m-0 p-0">
                                <div class="col forum-desc fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                                    <details>
                                        <summary>Исходный пост:</summary>
                                        <p><?php
                                        echo htmlspecialchars_decode($post['DATA']->first);
                                        ?></p>
                                    </details>
                                </div>
                                @include('topic.inc.reply', ['post' => $post])
                            </div>
                        @else
                            @include('topic.inc.reply', ['post' => $post])
                        @endif
                    @else
                        @include('topic.inc.reply', ['post' => $post])
                    @endif
                    <hr class="my-2">
                    {{-- Подпись ⋙ 👍 --}}
                    <div class="col-12 mb-3 mt-1 px-0">
                        <div class="row">
                            <div class="col d-flex justify-content-start align-items-center text-center ">
                                <i class="fa-regular fa-comment-dots me-1" style="color:rgb(0, 0, 116)"></i> <span style="color:rgb(0, 0, 116)">0</span>
                            </div>
                            <div class="col d-flex justify-content-end align-items-center text-center">
                                @include('topic.inc.like', ['model' => $model])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
