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
                <div class="mx-2">
                    <i class="fa-regular fa-bell" width="18" height="18"></i>
                </div>
                <div class="mx-2">
                    <i class="fa-regular fa-envelope" width="18" height="18"></i>
                </div>
                <div class="mx-2 ms-3">
                    @if (!is_null($model['user']) && !is_null($model['user']['avatar'])) <img class="min-avatar" alt="avatar" src="/storage{{ $model['user']['avatar'] }}">
                    @elseif(!is_null($model['user']) && is_null($model['user']['avatar']))
                    <img class="min-avatar" alt="avatar" src="/images/av.png"> @endif
                    @if (!is_null($model['user']))
                    <a class="text-break" href="{{ url('/user/' . $model['user']['id']) }}">{{ $model['user']['name'] }}</a>
                    @else
                        Гость
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
                @if (!is_null($model['user']) &&  !is_null($model['user']['avatar'])) <img class="min-avatar-mob mx-2" alt="avatar" src="/storage{{ $model['user']['avatar'] }}">
                @elseif(!is_null($model['user']) && is_null($model['user']['avatar']))
                <img class="min-avatar-mob mx-2" alt="avatar" src="/images/av.png"> @endif
                @if (!is_null($model['user']))
                    <a class="text-break" href="{{ url('/user/' . $model['user']['id']) }}">{{ $model['user']['name'] }}</a>
                    @else
                        Гость
                    @endif
                <svg class="mx-2 ms-3" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"></path>
                </svg>

                <svg class="mx-2" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                </svg>
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
