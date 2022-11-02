<ul class="pagination pagination-sm mb-2 ">
    <li class="page-item shadow-sm"><a style="box-shadow: null !important" class="page-link text-secondary" href="{{ url('t/' . $model['topicId'] . '/1') }}">Начало</a>
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
            <li class="page-item {{ $selected }} shadow-sm"><a style="box-shadow: null !important" class="page-link rounded-0 text-secondary" href="{{ url('t/' . $model['topicId'] . '/' . $i) }}">{{ $i }}</a></li>
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
            <li class="page-item {{ $selected }} shadow-sm"><a style="box-shadow: null !important" class="page-link rounded-0 text-secondary" href="{{ url('t/' . $model['topicId'] . '/' . $i) }}">{{ $i }}</a></li>
        @endfor
    @endif
    @if ($model['pages'] != $model['page'] && $model['pages'] > 0)
        <li class="page-item shadow-sm"><a style="box-shadow: null !important" class="page-link rounded-0 text-secondary" href="{{ url('t/' . $model['topicId'] . '/end') }}">Конец</a></li>
    @endif
</ul>
