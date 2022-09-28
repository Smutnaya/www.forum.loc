<div class="accordion accordion-flush d-block d-md-none" id="accordionFlushExample">
    <div class="accordion-item" style="background:#f1e9c2; color:#050505;">
        <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed fs-6 pt-0 pb-1" type="button" style="background:#f1e9c2; color:#050505; box-shadow: none !important;" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Форумы
            </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body fs-6 pt-1 pb-0">
                <ul class="navbar-nav mr-auto sidenav fs-6">
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
            </div>
        </div>
    </div>
</div>
<hr class="hr-color d-md-none d-sm-block mt-1">
