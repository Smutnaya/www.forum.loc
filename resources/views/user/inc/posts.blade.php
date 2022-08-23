<div class="my-2 mt-3 title-shadow fw-bold">Последние ответы:</div>

@foreach ($model['user_posts'] as $post)
    <div class="my-1">
        <div class="text-break">
            <a href="{{ url('/t/' . $post['topic_id']) }}"><span style="color:#4e5256">{{ $post['date'] }}</span></a> &nbsp; <a href="{{ url('/t/' . $post['topic_id']) }}"><span class="fw-bolder"> {{ $post['topic_title'] }}</span></a>
            <span class="forum-desc"> &nbsp; (
                <a style="color:#4e5256" href="{{ url('/f/' . $post['forum_id']) }}"><span>{{ $post['forum_title'] }}</span></a> &bull;
                <a style="color:#4e5256" href="{{ url('/s/' . $post['section_id']) }}"><span>{{ $post['section_title'] }}</span></a>
                )</span>
        </div>
        {{-- <div class="px-3">
            <?php
            echo htmlspecialchars_decode($post['text']);
            ?>
        </div> --}}
    </div>
@endforeach
