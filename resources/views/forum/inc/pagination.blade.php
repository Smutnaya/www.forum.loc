{{-- @dd($model['pages']) --}}

<ul class="pagination pagination-sm m-0">
    <li class="page-item"><a class="page-link text-secondary"
            href="{{ url('f/' . $model['forumId'] . '/1') }}">Начало</a>
    </li>
    @if ($model['page'] < 3)
        @for ($i = 1; $i <= 3; $i++)
            <?php
            if ($i > $model['pages']) {
                continue;
            }
            if ($i == $model['page']) {
                $selected = 'active';
            } else {
                $selected = '';
            } ?>
            <li class="page-item {{ $selected }}"><a class="page-link rounded-0 text-secondary"
                    href="{{ url('f/' . $model['forumId'] . '/' . $i) }}">{{ $i }}</a></li>
        @endfor
    @else
        @for ($i = $model['page'] - 1; $i <= $model['page'] + 1; $i++)
            <?php if ($i >= $model['pages'] + 1) {
                continue;
            }
            if ($i == $model['page']) {
                $selected = 'active';
            } else {
                $selected = '';
            } ?>
            <li class="page-item {{ $selected }}"><a class="page-link rounded-0 text-secondary"
                    href="{{ url('f/' . $model['forumId'] . '/' . $i) }}">{{ $i }}</a></li>
        @endfor
    @endif
    @if ($model['pages'] != $model['page'] && $model['pages'] > 0)
        <li class="page-item"><a class="page-link rounded-0 text-secondary"
                href="{{ url('f/' . $model['forumId'] . '/end') }}">Конец</a></li>
    @endif
</ul>

