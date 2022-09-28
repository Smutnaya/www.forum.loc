@php
use App\AppForum\Helpers\ForumHelper;
@endphp
@extends('layouts.user')
@section('title-block')
    @if (!is_null($model['user_inf']))
        {{ $model['user_inf']['name'] }} - Форум игры Времена Смуты
    @else
        Форум игры Времена Смуты
    @endif
@endsection
@section('content')
    {{-- @dd($model) --}}
    <div class="container-fluid px-0 m-0">
        @if ($errors->has('message'))
            <div class="alert alert-success mb-1" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ $errors->first('message') }}</div>
        @endif
        @if (Session::has('message'))
            <div class="alert alert-success" style="color: rgb(0 0 0 / 84%) !important; background-color: #9b000029 !important; border-color: #5c4f4f1c !important;">{{ Session::get('message') }}</div>
        @endif
        @if (Session::has('messageCancel'))
            <div style="color: rgb(0 0 0 / 84%) !important; background-color: #009b7421 !important; border-color: #4f5c541c !important; border: 1px solid transparent;
            border-radius: 0.25rem; text-align: center;">{{ Session::get('messageCancel') }}</div>
        @endif
        <h6 class="text-secondary title-shadow pt-1 pb-2">Информация о пользователе</h6>
        <div class="col-12 inf text-centre py-2 p-2 pb-3 shadow-sm fs-5" style="background: rgb(246, 240, 204);">

            @if (is_null($model['user_inf']))
                <div class="my-3 mb-5 centre error" style="color:red">Данные не найдены</div>
            @else
                <div class="row" style="text-align: center !important;">
                    <div class="col-lg-3 col-12 my-1 text-break ">
                        <div class="col">
                            @if (!is_null($model['user']))
                                @if ($model['user_inf']['id'] == $model['user']['id'] || $model['user']['role_id'] > 8 || $model['user']['role_id'] == 4)
                                    <div class="btn-group p-0 pe-1">
                                        <button class="btn btn-lg m-0 p-0" style="border: 0em !important; box-shadow: 0 0 0 0rem !important;" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="">
                                            <i class="fa-regular fa-image p-0 d-inline" style="color:#4e5256 !important;" title="Аватар"></i>
                                        </button>
                                        <ul class="dropdown-menu p-2 m-0" style="background: #f9f5dc">
                                            @if ($model['user_inf']['id'] == $model['user']['id'] || $model['user']['role_id'] > 10)
                                                <li>
                                                    <a onclick="toggleImage()" href="#">
                                                        <i class="fa-regular fa-image pt-1 d-inline" style="color:#4e5256 !important;"></i> Загрузить аватар
                                                    </a>
                                                </li>
                                            @endif
                                            @if (!is_null($model['user_inf']['avatar']))
                                                <li class="mt-1">
                                                    <form action='{{ url('/u/' . $model['user_inf']['id'] . '/image_del') }}' method="post">
                                                        @csrf
                                                        <a href="#">
                                                            <button type="submit" style="border: 0 !important; background: #f9f5dc; outline: 0 !important;" class="p-0">
                                                                <i class="fa-regular fa-trash-can" style="width: 15px !important"></i> Удалить аватар
                                                            </button>
                                                        </a>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            @endif
                            <a class="fw-bold text-break d-inline" href="{{ url('/user/' . $model['user_inf']['id']) }}">{{ $model['user_inf']['name'] }}</a>
                            @include('user.inc.image', ['model' => $model])
                        </div>

                        <div>
                            @if ($model['user_inf']['online'] == 'online')
                                <span class="fw-bolder pt-1" style="color: #006843 !important; font-size: 11pt;">online</span>
                            @else
                                <span class="pt-1" style="color:#4e5256 !important; font-size: 11pt;">{{ $model['user_inf']['online'] }}</span>
                            @endif
                        </div>


                        @if (!is_null($model['user']))
                            @if ($model['user']['role_id'] > 1)
                                <div class="text-center">
                                    <span class="forum-desc" title="вход">{{ $model['user']['ip'] }}</span>
                                    @if (!is_null($model['user']['ip_online']))
                                        &middot; <span class="forum-desc" title="последняя активность">{{ $model['user_inf']['ip_online'] }}</span>
                                    @endif
                                </div>
                            @endif
                        @endif
                        <div class="text-center d-flex justify-content-center pt-1">
                            <div class="col-lg-3 col-md-4" style="max-width: 185px; min-width: 140px">
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-center"><img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important;" class="max-avatar rounded" alt="Cinque Terre" @if (!is_null($model['user_inf']) && !is_null($model['user_inf']['avatar'])) src="/storage{{ $model['user_inf']['avatar'] }}"
                                        @else
                                        src="/images/av.png" @endif></div>
                                    @if ($model['roles'])
                                        <div type="button" data-bs-toggle="modal" data-bs-target="#exampleModal1" class="col-12 fw-bold text-black mt-2 text-break text-center" style="font-size: 11pt; {{ $model['user_inf']['style'] }}" title="Нажмите, чтобы изменить статус пользователя">{{ $model['user_inf']['role'] }}</div>
                                    @else
                                        <div class="col-12 fw-bold text-black mt-2 text-break text-center" style="font-size: 11pt; {{ $model['user_inf']['style'] }}">{{ $model['user_inf']['role'] }}</div>
                                    @endif
                                </div>
                                @if (!is_null($model['user']))
                                    @if ($model['user']['role_id'] > 1 && $model['user_inf']['role_id'] > 1)
                                        <div class="col-12 fw-bold text-black text-break text-center lh-sm" style="font-size: 8pt;">{{ $model['user_inf']['roleModer'] }}</div>
                                    @elseif ($model['user']['role_id'] > 1)
                                        @if ($model['other_role_inf'] && !$model['other_role_bf_inf'])
                                            <div class="col-12 fw-bold text-black text-break text-center lh-sm" style="font-size: 8pt;" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal1" type="button">индивидуальный доступ</div>
                                        @endif
                                        @if ($model['other_role_bf_inf'])
                                            <div class="col-12 fw-bold text-black text-break text-center lh-sm" style="font-size: 8pt;" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal1" type="button">индивидуальный доступ (модерация)</div>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9 col-12 d-lg-block d-none" style="color:#4e5256 !important;">
                        <div class="col-12 text-end">
                            @if (!is_null($model['user']))
                                @if (($model['user']['role_id'] > 1 && $model['user']['role_id'] != $model['user_inf']['role_id']) || ($model['other_role_bf'] && $model['user']['role_id'] >= $model['user_inf']['role_id'] && $model['user']['id'] != $model['user_inf']['id']))
                                    <i onclick="toggleBan()" type="button" class="fa-solid fa-ban me-2 ms-1 text-end" title="выдать бан"></i>
                                @endif
                                @if ($model['user']['role_id'] > 1 || $model['other_role_bf'] || $model['user']['id'] == $model['user_inf']['id'])
                                    <i onclick="toggleBanInf()" type="button" class="fa-solid fa-circle-info me-2 ms-1 text-end" title="информация о банах"></i>
                                @endif
                                @if ($model['user']['role_id'] > 10 && $model['user']['role_id'] != $model['user_inf']['role_id'])
                                    @if ($model['user_inf']['ban_message'])
                                        <form style="display: inline;" method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/ban_message') }}'>
                                            @csrf
                                            <input type="submit" class="btn-check btn-id" name="check[]" id="b{{ $model['user_inf']['id'] }}" value="{{ $model['user_inf']['id'] }}">
                                            <label for="b{{ $model['user_inf']['id'] }}">
                                                <span type="button" class="fa-stack" style="width: 20px;" title="бан сообщений">
                                                    <i class="fa-solid fa-x fa-stack-1x" style="color:#6a0000; font-size: 10px;"></i>
                                                    <i class="fa-regular fa-message fa-stack-1x"></i>
                                                </span>
                                            </label>
                                        </form>
                                    @else
                                        <form style="display: inline;" method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/ban_message') }}'>
                                            @csrf
                                            <input type="submit" class="btn-check btn-id" name="check[]" id="b{{ $model['user_inf']['id'] }}" value="{{ $model['user_inf']['id'] }}">
                                            <label for="b{{ $model['user_inf']['id'] }}">
                                                <span type="button" class="fa-stack" style="width: 20px;" title="бан сообщений">
                                                    <i class="fa-regular fa-message fa-stack-1x"></i>
                                                    <i class="fa-solid fa-check fa-stack-1x align-middle" style="color:#6a0000; font-size: 12px;"></i>
                                                </span>
                                            </label>
                                        </form>
                                    @endif
                                @endif
                                @if ($model['user']['role_id'] != $model['user_inf']['role_id'])
                                    {{-- @if ($model['user']['role_id'] == 4 || $model['user']['role_id'] > 8)
                                        <i class="fa-regular fa-circle-user me-2 ms-1 text-end" title="доступы"></i>
                                    @endif --}}
                                    @if ($model['user']['role_id'] > 10 && $model['user_inf']['role_id'] <= $model['user']['role_id'])
                                        <i onclick="toggleRoles()" type="button" class="fa-brands fa-earlybirds me-2 ms-2 text-end" style="color: #6a0000;" title="доп.доступы"></i>
                                    @endif
                                @endif
                            @endif
                        </div>

                        <div class="col-12">
                            <div class="row" style="min-height: 215px">
                                <div class="col-4 d-flex justify-content-center align-items-center" style="color:#4e5256">ответов: <span style="color:rgb(0, 0, 116)" class="fw-bolder">&nbsp;{{ $model['user_inf']['DATA']->post_count }}</span></div>
                                <div class="col-4 d-flex justify-content-center align-items-center" style="color:#4e5256">рейтинг: <span style="color: @if ($model['user_inf']['DATA']->like < 0) #6a0000 @else #0e583d @endif " class="fw-bolder">&nbsp;{{ $model['user_inf']['DATA']->like }}</span></div>
                                <div class="col-4 d-flex justify-content-center align-items-center" style="color:#4e5256">загрузка файлов:<span style="color:black">&nbsp;{{ $model['user_inf']['limit'] }}/20 Мб</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-lg-none d-sm-block" style="color:#4e5256 !important;">
                        <div class="col-12 text-center">
                            <span class="text-muted"><i class="fa-regular fa-message me-1" style="color:rgb(0, 0, 116)" title="Ответы"></i> <span style="color:rgb(0, 0, 116)">{{ $model['user_inf']['DATA']->post_count }}</span></span>
                            @if ($model['user_inf']['DATA']->like < 0)
                                <span class="text-muted"><i class="fa-regular fa-thumbs-down me-1 ms-2" style="color: #6a0000" title="Рейтинг"></i> <span style="color:#6a0000">{{ $model['user_inf']['DATA']->like }}</span></span>
                            @else
                                <span class="text-muted"><i class="fa-regular fa-thumbs-up me-1 ms-2" style="color:#0e583d" title="Рейтинг"></i> <span style="color:#0e583d">{{ $model['user_inf']['DATA']->like }}</span></span>
                            @endif
                            <span class="ms-2" style="color:#342d29"><i class="fa-regular fa-file-image me-1"></i>&nbsp;{{ $model['user_inf']['limit'] }}/20мб</span>
                            <div class="col-12">
                                @if (!is_null($model['user']))
                                    @if (($model['user']['role_id'] > 1 && $model['user']['role_id'] != $model['user_inf']['role_id']) || ($model['other_role_bf'] && $model['user']['role_id'] >= $model['user_inf']['role_id'] && $model['user']['id'] != $model['user_inf']['id']))
                                        <i onclick="toggleBan()" type="button" class="fa-solid fa-ban me-2 ms-1 text-end" title="выдать бан" id="btn-user_ban"></i>
                                    @endif
                                    @if ($model['user']['role_id'] > 1 || $model['other_role_bf'] || $model['user']['id'] == $model['user_inf']['id'])
                                        <i onclick="toggleBanInf()" type="button" class="fa-solid fa-circle-info me-2 ms-1 text-end" title="информация о банах"></i>
                                    @endif
                                    @if ($model['user']['role_id'] > 10)
                                        @if ($model['user_inf']['ban_message'])
                                            <form style="display: inline;" method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/ban_message') }}'>
                                                @csrf
                                                <input type="submit" class="btn-check btn-id" name="check[]" id="b{{ $model['user_inf']['id'] }}" value="{{ $model['user_inf']['id'] }}">
                                                <label for="b{{ $model['user_inf']['id'] }}">
                                                    <span type="button" class="fa-stack" style="width: 20px;" title="бан сообщений">
                                                        <i class="fa-solid fa-x fa-stack-1x" style="color:#6a0000; font-size: 10px;"></i>
                                                        <i class="fa-regular fa-message fa-stack-1x"></i>
                                                    </span>
                                                </label>
                                            </form>
                                        @else
                                            <form style="display: inline;" method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/ban_message') }}'>
                                                @csrf
                                                <input type="submit" class="btn-check btn-id" name="check[]" id="b{{ $model['user_inf']['id'] }}" value="{{ $model['user_inf']['id'] }}">
                                                <label for="b{{ $model['user_inf']['id'] }}">
                                                    <span type="button" class="fa-stack" style="width: 20px;" title="бан сообщений">
                                                        <i class="fa-regular fa-message fa-stack-1x"></i>
                                                        <i class="fa-solid fa-check fa-stack-1x align-middle" style="color:#6a0000; font-size: 12px;"></i>
                                                    </span>
                                                </label>
                                            </form>
                                        @endif
                                    @endif
                                    @if ($model['user']['role_id'] != $model['user_inf']['role_id'])
                                        {{-- @if ($model['user']['role_id'] == 4 || $model['user']['role_id'] > 8)
                                            <i class="fa-regular fa-circle-user me-2 ms-1 text-end" title="доступы"></i>
                                        @endif --}}
                                        @if ($model['user']['role_id'] > 10)
                                            <i onclick="toggleRoles()" type="button" class="fa-brands fa-earlybirds me-2 ms-2 text-end" style="color: #6a0000;" title="доп.доступы"></i>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <div class="modal fade scroll" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered p-2" style="max-width: 380px">
                <div class="modal-content py-3 px-4" style="background: #f1e9c2;">
                    <form method='post' action='{{ url('/u/' . $model['user_inf']['id'] . '/role') }}'>
                        @csrf
                        <p class="fw-bold my-2">Новый статус:</p>
                        <div class="overflow-auto mb-3 border-bottom" style="max-height: 150px; border-color: #e3dbb7 !important;">
                            @foreach ($model['rolesInstall'] as $role)
                                <div class="form-check p-0 new-tema">
                                    <input type="submit" class="btn-check btn-id" name="check[]" id="r{{ $role['id'] }}" value="{{ $role['id'] }}">
                                    <label class="btn btn-outline-primary p-0" for="r{{ $role['id'] }}">
                                        <span class="d-sm-inline" style="{{ ForumHelper::roleStyle($role['id']) }}">{{ $role['role'] }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('user.inc.roles', ['model' => $model])
    @include('user.inc.ban', ['model' => $model])
    @include('user.inc.ban_inf', ['model' => $model])
    @include('user.inc.posts', ['model' => $model])
    @endif
    </div>
    </div>

    <script>
        var el = document.getElementById('user_ban');
        var el1 = document.getElementById('user_ban_inf');

        function toggleBan() {
            if (el.style.display == "none") {
                el.style.display = "";
                el1.style.display = "none";
                roles.style.display = "none";
            } else {
                el.style.display = "none";
            }
        }

        function toggleBanInf() {
            if (el1.style.display == "none") {
                el1.style.display = "";
                el.style.display = "none";
                roles.style.display = "none";
            } else {
                el1.style.display = "none";
            }
        }

        var ban_cancel = document.getElementById('ban_cancel');

        function toggleBanCancel() {
            if (ban_cancel.style.display == "none") {
                ban_cancel.style.display = "";
            } else {
                ban_cancel.style.display = "none";
            }
        }

        function userBanHide() {
            el.style.display = "none";
        }

        var roles = document.getElementById('roles');

        function toggleRoles() {
            if (roles.style.display == "none") {
                roles.style.display = "";
                el1.style.display = "none";
                el.style.display = "none";
            } else {
                roles.style.display = "none";
            }
        }

        var image = document.getElementById('image');

        function toggleImage() {
            image.style.display = "";
        }

        function toggleImageHide() {
            image.style.display = "none";
        }

        $(document).ready(function() {
            $('[data-bs-toggle="popover"]').popover();
        });
    </script>

@endsection
