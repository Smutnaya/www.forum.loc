<header>
    <nav class="navbar navbar-expand-md d-none d-sm-none d-md-block fs-5">
        <div class="container-fluid">
            <a class="navbar-brand fs-4 naw-link py-0 pe-0" href={{ url('/main') }}><img class="logo" alt="logo" src="/images/vs logo.png"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                    <li class="nav-item">
                        <a aria-current="page" class="naw-link" href="{{ url('/fs') }}">Форумы</a>
                    </li>
                    <li class="nav-item">
                        <a aria-current="page" class="naw-link" disabled>Газеты и блоги</a>
                    </li>
                    <li class="nav-item">
                        <a aria-current="page" class="naw-link" disabled>FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a aria-current="page" class="naw-link" href="https://vsmuta.com">В игру</a>
                    </li>
                </ul>

                @if (!is_null($model['user']))
                    @if ($model['user']['role_id'] > 2 && $model['complaints']->count() > 0)
                        <a href="{{ url('/cw') }}">
                            <i class="fa-regular fa-hourglass-half me-3 align-middle" style="color: #7c0011;"></i>
                        </a>
                    @endif
                    @if (!is_null($model['message_new']))
                        @if ($model['message_new'] == 0)
                            <a href="{{ url('/message') }}"><i class="fa-regular fa-envelope align-middle" style="font-size: 25px;"></i></a>
                        @else
                            <a href="{{ url('/message') }}"><span class="position-relative">
                                    <i class="fa-regular fa-envelope align-middle fa-beat" style="font-size: 25px;"></i>
                                    <span class="position-absolute top-100 start-100 translate-middle badge border rounded-circle bg-success p-1 m-0 flicker" style="background-color: #0e7a32 !important; border-color: #0e7a32 !important;  left: 95% !important; top: 91% !important;"><span class="visually-hidden">unread messages</span></span>
                                </span>
                            </a>
                        @endif
                    @endif
                @endif
                <div class="mx-2 ms-3">
                    @if (!is_null($model['user']) && !is_null($model['user']['avatar']))
                        <span class="position-relative">
                            <a type="button" href="{{ url('/user/' . $model['user']['id']) }}">
                                <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important; border-radius: 20px !important;" class="min-avatar" alt="avatar" src="/storage{{ $model['user']['avatar'] }}">
                            </a>
                            <a type="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" href="#">
                                <span class="fa-stack position-absolute" style="font-size: 7px; color: #000000bf; right: 0% !important; top: 95% !important;">
                                    <i class="fa-solid fa-circle fa-stack-2x"></i>
                                    <i class="fa-solid fa-chevron-up fa-flip-vertical fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu p-1 mt-3 text-center" style="font-size: 12px; background: #f9f5dc; left: -70px; min-width: 120px !important;" aria-labelledby="dropdownMenuLink">
                                <li class="fw-bold">
                                    {{ $model['user']['name'] }}
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-1">
                                </li>
                                <li>
                                    <form method="POST" action="{{ url('/logout') }}">@csrf<input type="submit" value="Выход" style="background-color: transparent !important; border: none !important; box-shadow: none !important;"></form>
                                </li>
                            </ul>
                        </span>
                    @elseif(!is_null($model['user']) && is_null($model['user']['avatar']))
                        <span class="position-relative">
                            <a type="button" href="{{ url('/user/' . $model['user']['id']) }}">
                                <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important; border-radius: 20px !important;" class="min-avatar" alt="avatar" src="/images/av.png">
                            </a>
                            <a type="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" href="#">
                                <span class="fa-stack position-absolute" style="font-size: 7px; color: #000000bf; right: 0% !important; top: 95% !important;">
                                    <i class="fa-solid fa-circle fa-stack-2x"></i>
                                    <i class="fa-solid fa-chevron-up fa-flip-vertical fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu p-1 mt-3 text-center" style="font-size: 12px; background: #f9f5dc; left: -70px; min-width: 120px !important;" aria-labelledby="dropdownMenuLink">
                                <li class="fw-bold">
                                    {{ $model['user']['name'] }}
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-1">
                                </li>
                                <li>
                                    <form method="POST" action="{{ url('/logout') }}">@csrf<input type="submit" value="Выход" style="background-color: transparent !important; border: none !important; box-shadow: none !important;"></form>
                                </li>
                            </ul>

                            {{-- <a type="button" href="{{ url('/message') }}">
                                <span class="fa-stack position-absolute" style="font-size: 7px; color: #000000bf; left: 60% !important; top: 95% !important;">
                                    <i class="fa-solid fa-circle fa-stack-2x"></i>
                                    <i class="fa-solid fa-chevron-up fa-flip-vertical fa-stack-1x fa-inverse"></i>
                                </span>
                            </a> --}}
                        </span>
                    @endif
                    @if (!is_null($model['user']))
                        {{-- <a class="text-break" href="{{ url('/user/' . $model['user']['id']) }}">{{ $model['user']['name'] }}</a> --}}
                    @else
                        <a href="{{ route('login') }}">Вход</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-md d-sm-block d-sx-block d-lx-none d-lg-none d-md-none py-0">
        <div class="container-fluid gx-0 ">
            <div class="text-start col-7 mb-1">
                <a href={{ url('/main') }}><img class="min-logo" src="/images/vs logo.png"></a><button class="btn btn-sm text-start p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop" style="box-shadow: none !important;">
                    <h5 id="offcanvasTopLabel" style="margin-bottom: 0 !important;">
                        <span class="ps-1">Времена Смуты</span>
                    </h5>
                </button>
            </div>
            <div class="text-end col-5">
                {{-- <div class="mx-2">
                    <i class="fa-regular fa-bell" width="18" height="18"></i>
                </div> --}}

                @if (!is_null($model['user']))
                    @if ($model['user']['role_id'] > 2 && $model['complaints']->count() > 0)
                        <a href="{{ url('/cw') }}">
                            <i class="fa-regular fa-hourglass-half me-3 align-middle" style="color: #7c0011;"></i>
                        </a>
                    @endif
                    @if (!is_null($model['message_new']))
                        @if ($model['message_new'] == 0)
                            <a href="{{ url('/message') }}"><i class="fa-regular fa-envelope align-middle" style="font-size: 20px;"></i> </a>
                        @else
                            <a href="{{ url('/message') }}"><span class="position-relative">
                                    <i class="fa-regular fa-envelope align-middle fa-beat" style="font-size: 20px;"></i>
                                    <span class="position-absolute translate-middle badge border rounded-circle bg-success p-1 m-0 flicker" style="background-color: #0e7a32 !important; border-color: #0e7a32 !important; left: 80% !important; top: 95% !important;"><span class="visually-hidden">unread messages</span></span>
                                </span>
                            </a>
                        @endif
                    @endif
                @endif
                <span class="mx-2 ms-2">
                    @if (!is_null($model['user']) && !is_null($model['user']['avatar']))
                        <span class="position-relative">
                            <a type="button" href="{{ url('/user/' . $model['user']['id']) }}">
                                <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important; border-radius: 20px !important; padding-left: 0px !important; padding-top: 0px !important;" class="min-avatar-mob p-0" alt="avatar" src="/storage{{ $model['user']['avatar'] }}">
                            </a>
                            <a type="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" href="#">
                                <span class="fa-stack position-absolute" style="font-size: 7px; color: #000000bf; right: 0% !important; top: 95% !important;">
                                    <i class="fa-solid fa-circle fa-stack-2x"></i>
                                    <i class="fa-solid fa-chevron-up fa-flip-vertical fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu p-1 mt-3 text-center" style="font-size: 12px; background: #f9f5dc; left: -75px; min-width: 120px !important;" aria-labelledby="dropdownMenuLink">
                                <li class="fw-bold">
                                    {{$model['user']['name']}}
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-1">
                                </li>
                                <li>
                                    <form method="POST" action="{{ url('/logout') }}">@csrf<input type="submit" value="Выход" style="background-color: transparent !important; border: none !important; box-shadow: none !important;"></form>
                                </li>
                            </ul>
                        </span>
                    @elseif(!is_null($model['user']) && is_null($model['user']['avatar']))
                        <span class="position-relative">
                            <a type="button" href="{{ url('/user/' . $model['user']['id']) }}">
                                <img style="background-color: #f9f5dc !important; border: 1px solid #d4d1bb9e !important; border-radius: 20px !important; padding-left: 0px !important; padding-top: 0px !important;" class="min-avatar-mob p-0" alt="avatar" src="/images/av.png">
                            </a>
                            <a type="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" href="#">
                                <span class="fa-stack position-absolute" style="font-size: 7px; color: #000000bf; right: 0% !important; top: 75% !important;">
                                    <i class="fa-solid fa-circle fa-stack-2x"></i>
                                    <i class="fa-solid fa-chevron-up fa-flip-vertical fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu p-1 mt-3 text-center" style="font-size: 12px; background: #f9f5dc; left: -75px; min-width: 120px !important;" aria-labelledby="dropdownMenuLink">
                                <li class="fw-bold">
                                    {{$model['user']['name']}}
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-1">
                                </li>
                                <li>
                                    <form method="POST" action="{{ url('/logout') }}">@csrf<input type="submit" value="Выход"style="background-color: transparent !important; border: none !important; box-shadow: none !important;"></form>
                                </li>
                            </ul>
                        </span>
                    @endif

                    @if (!is_null($model['user']))
                        {{-- <a class="text-break" href="{{ url('/user/' . $model['user']['id']) }}">{{ $model['user']['name'] }}</a> --}}
                    @else
                        <a href="{{ route('login') }}">Вход</a>
                    @endif
                </span>
            </div>
        </div>
        <div class="offcanvas offcanvas-top g-0 p-0" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel" style="height: 245px;">
            <div class="offcanvas-header pt-3 pb-2 border-bottom" style="background:#eee9e0;">
                <h5 class="fs-5" id="offcanvasTopLabel">Форум игры Времена Смуты</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body pt-1 pb-3" style="background:#f8f3eb;">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item my-1">
                        <a class="py-1" aria-current="page" href="{{ url('/fs') }}">Форумы</a>
                    </li>
                    <li class="nav-item my-1">
                        <a class="py-1" aria-current="page" disabled>Газеты и блоги</a>
                    </li>
                    <li class="nav-item my-1">
                        <a class="py-1" aria-current="page" disabled>FAQ</a>
                    </li>
                    <li class="nav-item my-1">
                        <a class="py-1" aria-current="page" href="https://vsmuta.com">В игру</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-2">
                    </li>
                    <li class="nav-item mt-1">
                        <a class="py-1 fw-bold" aria-current="page" href={{ url('/main') }}>Главная</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <hr class="mt-0 mb-2 hr-color shadow-sm">

    <!-- Right Side Of Navbar -->
    {{-- <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a> --}}

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
    {{-- </li>
            @endguest
        </ul>
    </div> --}}






</header>
