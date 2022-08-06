<div class="col border border-ligh shadow-sm " style="background:#fdfddbb3;">
    <div class="row py-1" style="background:#ede8c775;">
        <div class="col fw-bold">
            Пользователи онлайн:
        </div>
    </div>
    <div class="row py-2">
        <div class="col fst-italic">
            @if (!is_null($model['onlines']))
            @foreach ($model['onlines'] as $online)
                <span style="{{$online['style']}}">{{ $online['name'] }}</span>
                @if (!$loop->last)
                    <span style="margin-left: -4px !important;">,</span>
                @endif
            @endforeach
        @endif
        </div>
    </div>
</div>
