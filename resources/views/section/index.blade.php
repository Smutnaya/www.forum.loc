@extends('layouts.forum')
@section('content')
    <div class="container-fluid m-x-0 p-0">
        <div class="forum-inf-color forum-line m-y-1 m-x-0 p-0">
            <div class="row forum-border forum-inf">
                <div class="centre col-md-10">
                    Название
                </div>
                <div class="centre col-md-1 forum-inf forum-stat-topic">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="centre col">
                                Тем
                            </div>
                            <div class="centre col">
                                постов
                            </div>
                        </div>
                    </div>
                </div>

                <div class="centre col-md-1 forum-stat-post">
                    LAST
                </div>
            </div>

        </div>

        @foreach ($model['forums'] as $forum)
            <div class="m-x-1 p-0">
                <div class="row forum-border">
                    <div class="col-md-10">
                        <div class="forum-title"><a href="{{ url('/f/' . $forum['id']) }}">{{ $forum['title'] }}
                            </a>
                        </div>
                        <div class="forum-desc">{{ $forum['description'] }}</div>
                    </div>

                    <div class="centre col-md-1 forum-inf forum-stat-topic">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="centre col forum-inf">
                                    Тем
                                </div>
                                <div class="centre col forum-inf">
                                    постов
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="centre col-md-1 forum-inf forum-stat-post">
                        LAST
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

{{-- <div class="centre col-md-">
    Тем
</div>
<div class="centre col-md-3">
    Постов
</div>
<div class="col-md-6 centre">
    Последнее сообщение
</div> --}}
