<div class="d-flex justify-content-between">

    @if (!is_null($model['user']))
        @if ((!$model['topic']['block'] && !$model['userBan'] && $model['topic']['forum_id'] != 38 && $model['topic']['forum_id'] != 39) || $model['newPost'])
            <div class="col d-grid gap-2 d-inline-flex justify-content-end mb-2">
                <a id="btn-post-field" class="btn btn-sm btn-dark btn-custom shadow-sm" style="width: 100px !important;">Комментарий</a>
            </div>
</div>

<div id="menu-post-field" style="display:none">
    <div class="row">
        <form method='post' action='{{ url('t/' . $model['topic']['id'] . '/post') }}'>
            @csrf
            {{-- @include('inc.ckeditor') --}}
            @include('topic.news.ckeditor')
            <div class="col d-grid gap-2 d-flex justify-content-end my-2">
                <a id="reset" class="btn btn-sm btn-dark btn-custom shadow-sm">Очистить</a>
                <input class="btn btn-sm btn-dark btn-custom shadow-sm" type="submit" value="Добавить">
            </div>
        </form>
    </div>
</div>
