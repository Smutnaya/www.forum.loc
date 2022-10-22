<div class="col m-0 p-0 border border-ligh shadow-sm mt-2" style="background:#ffffdeb5" {{-- style="background:#fdfddbb3;" --}}>
    <div class="row p-0 m-0" style="background:#f4efcd;">
        <div class="col my-1 fw-bold">
            Пользователи онлайн
        </div>
    </div>
    <div class="row py-2 m-0" style="font-size: 10pt;">
        <div class="col m-0 fst-italic">
            @if (!is_null($model['onlines']) && $model['onlines']->count() > 0)
                @foreach ($model['onlines'] as $online)
                    @if ($online['role_id'] == 1)
                        <a href="{{ url('/user/' . $online['user_id']) }}"><span style="{{ $online['style'] }} font-weight: normal !important;">{{ $online['name'] }}</span></a>
                    @else
                        <a href="{{ url('/user/' . $online['user_id']) }}"><span style="{{ $online['style'] }}">{{ $online['name'] }}</span></a>
                    @endif
                    @if (!$loop->last)
                        <span style="margin-left: -4px !important;">,</span>
                    @endif
                @endforeach
            @else
                ...
            @endif
        </div>
    </div>
</div>
