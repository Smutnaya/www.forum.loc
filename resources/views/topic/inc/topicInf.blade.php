<div class="row text-break d-flex justify-content-start align-items-center ps-0 py-1 mb-2" id="title">
    <div class="col-12">
        <span class="m-0 fs-3 title-shadow">
            <span class="text-secondary" title="ID темы">
                @if (!is_null($model['user']))
                    @if ($model['user']['role_id'] > 1 || $model['moder'] || $model['editor'])
                        [{{ $model['topic']['id'] }}]
                    @endif
                @endif
            </span>
            <span>
                {{ $model['topic']['title'] }}
            </span>
        </span>
        <span class="align-top" style="color: #7c0011; font-size: 12px;">
            @if ($model['topic']['pin'])
                <i class="fa fa-thumb-tack" style="padding-top: 13px;"></i>
            @endif
            @if ($model['topic']['hide'])
                <i class="fa-regular fa-eye-slash" style="padding-top: 13px;"></i>
            @endif
            @if ($model['topic']['block'])
                <i class="fa-solid fa-lock" style="padding-top: 13px;"></i>
            @endif
            @if ($model['topic']['moderation'])
                <i class="fa-regular fa-hourglass" style="padding-top: 13px;"></i>
            @endif
        </span>
        <span>
            @if ($model['topicEdit'])
                <i onclick="toggleEdit()" type="button" id="topic-edit" class="fa-solid fa-pencil ms-2 mt-1" style="color: #989e9a;" title="Редактировать тему"></i>
            @endif
            @if ($model['topicMove'])
                <i onclick="toggleMove()" type="button" id="topic-move" class="fa-solid fa-truck-arrow-right ms-3 mt-1" style="color: #989e9a;" title="Переместить тему" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
            @endif
        </span>
    </div>
</div>

