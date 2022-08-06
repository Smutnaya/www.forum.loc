@if (!is_null($model['user']))
    <span @if (!is_null($post['DATA']->like_name)) data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="
                                <?php
                                echo htmlspecialchars_decode($post['DATA']->like_name);
                                ?>" @endif>
        @if (is_null($post['like']) || $post['like']->action == 'dislike')
            <a href="{{ url('/p/' . $post['id'] . '/like/' . $model['pagination']['page']) }}" style="text-decoration: none !important;">
                <i class="fa-regular fa-thumbs-up" style="color:#0e583d"></i>
            </a>
        @elseif ($post['like']->action == 'like')
            <a href="{{ url('/p/' . $post['id'] . '/likem/' . $model['pagination']['page']) }}" style="text-decoration: none !important;">
                <i class="fa-solid fa-thumbs-up" style="color:#0e583d"></i>
            </a>
        @endif
        <span class="ms-1 me-3" style="color:0e583d">{{ $post['DATA']->like }}</span>
    </span>
    <span @if (!is_null($post['DATA']->dislike_name)) data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="
                                <?php
                                echo htmlspecialchars_decode($post['DATA']->dislike_name);
                                ?>" @endif>
        @if (is_null($post['like']) || $post['like']->action == 'like')
            <a href="{{ url('/p/' . $post['id'] . '/dislike/' . $model['pagination']['page']) }}" style="text-decoration: none !important;">
                <i class="fa-regular fa-thumbs-down" style="color: #6a0000"></i>
            </a>
        @elseif ($post['like']->action == 'dislike')
            <a href="{{ url('/p/' . $post['id'] . '/dislikem/' . $model['pagination']['page']) }}" style="text-decoration: none !important;">
                <i class="fa-solid fa-thumbs-down" style="color: #6a0000"></i>
            </a>
        @endif
        <span class="ms-1" style="color: #6a0000">{{ $post['DATA']->dislike }}</span>
    </span>
@else
    <span @if (!is_null($post['DATA']->like_name)) data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="
                                <?php
                                echo htmlspecialchars_decode($post['DATA']->like_name);
                                ?>" @endif>
        <i class="fa-regular fa-thumbs-up" style="color:#0e583d"></i>
        <span class="ms-1 me-3" style="color:0e583d">{{ $post['DATA']->like }}</span>
    </span>
    <span @if (!is_null($post['DATA']->dislike_name)) data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="
                                <?php
                                echo htmlspecialchars_decode($post['DATA']->dislike_name);
                                ?>" @endif>
        <i class="fa-regular fa-thumbs-down" style="color: #6a0000"></i>
        <span class="ms-1" style="color: #8b0000">{{ $post['DATA']->dislike }}</span>
    </span>
@endif
