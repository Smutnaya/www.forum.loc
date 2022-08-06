@section('aside')
        <ul class="navbar-nav mr-auto sidenav p-0 d-none d-md-block fs-6">
            @foreach ($model['sectionsAside'] as $section)
            <li class="nav-item p-0">
                <a class="m-0 p-0" href="{{ url('/s/'.$section['id']) }}">{{ $section['title'] }}</a>
            </li>
            @endforeach
        </ul>
        <br>
        {{--<div><img style="width: 50%; opacity: .25;" alt="logo" src="/images/knight.png"></div> --}}

@show
