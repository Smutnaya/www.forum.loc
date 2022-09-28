@section('aside')
    <div class="d-none d-md-block" style="position: sticky; top: 15px;">
        <ul class="navbar-nav mr-auto sidenav p-0 fs-6">
            @foreach ($model['sectionsAside'] as $section)
                <li class="nav-item p-0">
                    <div class="row">
                    <div style="width: 18px !important;" class="text-center col-2">
                        @if ($section['id'] == 3)
                            <a class="m-0 p-0" href="{{ url('/s/' . $section['id']) }}">
                                <i style="width: 18px !important;" class="fa-solid fa-shield-halved"></i>
                            </a>
                        @elseif ($section['id'] == 1)
                            <a class="m-0 p-0" href="{{ url('/s/' . $section['id']) }}">
                                <i style="width: 18px !important;" class="fa-brands fa-fort-awesome"></i>
                                {{-- <img style="height: 13px" alt="logo" src="/images/vs.png"></a> --}}
                            </a>
                        @elseif ($section['id'] == 2)
                            <a class="m-0 p-0" href="{{ url('/s/' . $section['id']) }}">
                                <i style="width: 18px !important;" class="fa-solid fa-comments"></i>
                            </a>
                        @elseif ($section['id'] == 4)
                            <a class="m-0 p-0" href="{{ url('/s/' . $section['id']) }}">
                                <i style="width: 18px !important;" class="fa-solid fa-chess-rook"></i>
                            </a>
                        @elseif ($section['id'] == 5)
                            <a class="m-0 p-0" href="{{ url('/s/' . $section['id']) }}">
                                <i style="width: 18px !important;" class="fa-solid fa-fan"></i>
                            </a>
                        @elseif ($section['id'] == 6)
                            {{-- <i class="fa-solid fa-book-bookmark"></i> --}}
                            <a class="m-0 p-0" href="{{ url('/s/' . $section['id']) }}">
                                <i style="width: 18px !important;" class="fa-solid fa-scroll"></i>
                            </a>
                        @elseif ($section['id'] == 7)
                            <a class="m-0 p-0" href="{{ url('/s/' . $section['id']) }}">
                                <i style="width: 18px !important; color: #6a0000" class="fa-solid fa-shield-cat"></i>
                            </a>
                        @endif
                    </div>
                    <div class="col-10">
                         <a class="m-0 p-0" @if ($section['id'] == 7) style="color: #6a0000" @endif href="{{ url('/s/' . $section['id']) }}">{{ $section['title'] }}</a>
                        </div>
                    </div>

                </li>
            @endforeach
        </ul>
        <br>
        <div class="mt-3 d-none d-md-block"><img style="width: 65%; opacity: .85; " alt="logo" src="/images/knight2.png"></div>
    </div>
@show
