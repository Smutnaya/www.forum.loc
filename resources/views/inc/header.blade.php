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
                        <a aria-current="page" class="naw-link" href=#>Газеты</a>
                    </li>
                    <li class="nav-item">
                        <a aria-current="page" class="naw-link" href=#>FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a aria-current="page" class="naw-link" href=#>В игру</a>
                    </li>
                </ul>

                {{-- <div class="mx-2">
                    <i class="fa-regular fa-bell" width="18" height="18"></i>
                </div> --}}
                @if (!is_null($model['user']))
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
                            <ul class="dropdown-menu p-1 mt-3 text-center" style="font-size: 11px; background: #f9f5dc; left: -10px; min-width: 65px !important;" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item p-0" style="background: #f9f5dc !important;" href="{{ url('/message') }}">Выход</a></li>
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
                            <ul class="dropdown-menu p-1 mt-3 text-center" style="font-size: 11px; background: #f9f5dc; left: -10px; min-width: 65px !important;" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item p-0" style="background: #f9f5dc !important;" href="{{ url('/message') }}">Выход</a></li>
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
                        Вход
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-md d-sm-block d-sx-block d-lx-none d-lg-none d-md-none py-0">
        <div class="container-fluid gx-0 ">
            <div class="text-start col-12 col-sm-7">
                <button class="btn btn-sm text-start py-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">
                    <h5 id="offcanvasTopLabel">
                        <img class="min-logo" src="/images/vs logo.png"><span class="ps-3">Форум игры Времена Смуты </span>
                    </h5>
                </button>
            </div>
            <div class="text-end my-1 col-12 col-sm-5">
                {{-- <div class="mx-2">
                    <i class="fa-regular fa-bell" width="18" height="18"></i>
                </div> --}}
                @if (!is_null($model['user']))
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
                            <ul class="dropdown-menu p-1 mt-3 text-center" style="font-size: 11px; background: #f9f5dc; left: -10px; min-width: 50px !important;" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item p-0" style="background: #f9f5dc !important;" href="{{ url('/message') }}">Выход</a></li>
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
                            <ul class="dropdown-menu p-1 mt-3 text-center" style="font-size: 11px; background: #f9f5dc; left: -10px; min-width: 50px !important;" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item p-0" style="background: #f9f5dc !important;" href="{{ url('/message') }}">Выход</a></li>
                            </ul>
                        </span>
                    @endif

                    @if (!is_null($model['user']))
                        {{-- <a class="text-break" href="{{ url('/user/' . $model['user']['id']) }}">{{ $model['user']['name'] }}</a> --}}
                    @else
                        Вход
                    @endif
                </span>
            </div>
        </div>
        <div class="offcanvas offcanvas-top g-0 p-0" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
            <div class="offcanvas-header py-3 border-bottom" style="background:#eee9e0;">
                <h5 class="fs-5" id="offcanvasTopLabel">Форум игры Времена Смуты</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body py-3" style="background:#f8f3eb;">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="py-1" aria-current="page" href="{{ url('/fs') }}">Форумы</a>
                    </li>
                    <li class="nav-item">
                        <a class="py-1" aria-current="page" href=#>Газеты</a>
                    </li>
                    <li class="nav-item">
                        <a class="py-1" aria-current="page" href=#>FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="py-1" aria-current="page" href=#>В игру</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-2">
                    </li>
                    <li class="nav-item">
                        <a class="py-1 fw-bold" aria-current="page" href={{ url('/main') }}>Главная</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
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
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
    <hr class="mt-0 mb-2 hr-color shadow-sm">





</header>
