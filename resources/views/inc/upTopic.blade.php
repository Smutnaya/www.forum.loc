@php
use App\AppForum\Helpers\ForumHelper;
@endphp
<div class="scroll">
    <div class="col border border-ligh shadow-sm overflow-auto text-break" style="background:#ffffdeb5; max-height: 303px; font-size: 10pt;">
        <div class="row m-0 fs-6 border-bottom" style="background:#f4efcd; position: sticky; top: 0px; border-bottom-color: #dee2e657 !important;">
            <div class="col py-1 fw-bold">
                Последние ответы
            </div>
        </div>
        @if ($model['last_posts']->count() > 0)
            @foreach ($model['last_posts'] as $last_post)
                <section class="py-2 m-0 mx-3 @if (!$loop->last) border-bottom" style="border-bottom-color: #e2d5ac !important; @endif">
                    <a style="color: #2a2a2a !important;" class="fw-bold" href="{{ url('/t/' . $last_post['id'] . '-' . $last_post['title_slug'] . '/end') }}">
                        <span class="news">{{ $last_post['title'] }}</span></a>
                    <div>
                        <a href="{{ url('/user/' . $last_post['DATA']->last_post->user_id) }}">{{ $last_post['DATA']->last_post->user_name }}</a>
                        &bull;
                        <span class="forum-desc" style="font-size: 8pt;">
                            {{ ForumHelper::timeFormat($last_post['DATA']->last_post->date) }}</span>
                    </div>
                </section>
            @endforeach
        @else
            <div class="col py-3 fst-italic">
                Здесь пока пусто ;(
            </div>
        @endif
    </div>
</div>
<div class="scroll">
    <div class="col mt-4 border border-ligh shadow-sm overflow-auto text-break" style="background:#ffffdeb5; max-height: 303px; font-size: 10pt;">
        <div class="row m-0 fs-6 border-bottom" style="background:#f4efcd; position: sticky; top: 0px; border-bottom-color: #dee2e657 !important;">
            <div class="col py-1 fw-bold">
                Новые темы
            </div>
        </div>
        @if ($model['new_topics']->count() > 0)
            @foreach ($model['new_topics'] as $new_topic)
                <section class="py-2 m-0 mx-3 @if (!$loop->last) border-bottom" style="border-bottom-color: #e2d5ac !important; @endif">
                    <a class="fw-bold" href="{{ url('/t/' . $new_topic['id'] . '-' . $new_topic['title_slug'] . '/end') }}">
                        <span class="news">{{ $new_topic['title'] }}</span></a>
                    <div>
                        <a style="color: #2a2a2a !important;" href="{{ url('/user/' . $new_topic['user_id']) }}">{{ $new_topic['user_name'] }}</a>
                        &bull;
                        <span class="forum-desc" style="font-size: 8pt;">
                            {{ $new_topic['datetime'] }}</span>
                    </div>
                </section>
            @endforeach
        @else
            <div class="col py-3 fst-italic">
                Здесь пока пусто ;(
            </div>
        @endif
    </div>
</div>
