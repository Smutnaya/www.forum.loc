<div class="col border border-ligh shadow-sm" style="background:#ffffdeb5" {{-- style="background:#fdfddbb3;" --}}>
    <div class="row py-1" style="background:#f4efcd;">
        <div class="col fw-bold">
            Пользователи онлайн
        </div>
    </div>
    <div class="row py-2" style="font-size: 10pt;">
        <div class="col fst-italic">
            @if (!is_null($model['onlines']))
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
            @endif
        </div>
    </div>
</div>
