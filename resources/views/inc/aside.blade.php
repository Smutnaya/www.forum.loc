@section('aside')
    <div style="position: sticky; top: 15px;">
        <ul class="navbar-nav mr-auto sidenav p-0 d-none d-md-block fs-6">
            @foreach ($model['sectionsAside'] as $section)
                <li class="nav-item p-0">
                    <a class="m-0 p-0" href="{{ url('/s/' . $section['id']) }}">{{ $section['title'] }}</a>
                </li>
            @endforeach
        </ul>
        <br>
        <div class="mt-3 d-none d-md-block"><img style="width: 65%; opacity: .85; " alt="logo" src="/images/knight2.png"></div>
    </div>
@show
