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
                {{ $online['name'] }}
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        @endif
        </div>
    </div>
</div>
