@php
use App\AppForum\Helpers\ForumHelper;
@endphp

@foreach ($model['comments'] as $comment)
    <div class="row border border-ligh shadow-sm mx-0 mb-2 my-1 overflow-hidden" style="background: #f5edc4; font-size: 0.9em;">
        <div class="col-12 py-1">
            <div class="row">
                <div class="col">
                    <span style="font-size: 0.9em;">
                        <a class="fw-bold text-break" href="{{ url('/user/' . $comment['user_id']) }}">{{ $comment['user_name'] }}</a>
                    </span>
                    &nbsp;&bull;&nbsp;
                    <span class="forum-desc">{{ $comment['date'] }}</span>
                    @if (!is_null($comment['ip']))
                        @if ($model['moder'] || $model['editor'])
                            &nbsp;&bull;&nbsp;
                            <span class="forum-desc">{{ $comment['ip'] }}</span>
                        @endif
                    @endif
                </div>
                <div class="col-1 d-inline-flex justify-content-end align-items-center">
                    @if ($model['moder'] || $model['editor'] || (!is_null($model['user']) && $model['user']['id'] == $comment['user_id']))
                        <form method='post' action='{{ url('/c/' . $comment['id'] . '/del') }}'>
                            @csrf
                            <button type="submit" class="btn btn-sm m-0 p-0 form-check-input d-flex justify-content-end align-items-center" style="background-color: transparent !important; border-color: transparent !important;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12" style="background: #fdfcda">
            <div class="py-2">
                <?php
                echo htmlspecialchars_decode($comment['text']);
                ?>
            </div>
            <div class="row mx-0">
                @if (!is_null($comment['DATA']->user_name_edit) && !is_null($comment['DATA']->date_edit))
                    <div class="col forum-desc fst-italic p-0 d-flex justify-content-start align-items-center text-center">
                        <i class="fa-solid fa-pencil me-1"></i> &nbsp; <span class="fw-bold">{{ $comment['DATA']->user_name_edit }} &middot; &nbsp;</span>
                        {{ ForumHelper::timeFormat($comment['DATA']->date_edit) }}
                    </div>
                @endif
                {{-- <hr class="my-21">
                <div class="col-12 px-0">
                    <div class="row">
                        <div class="col d-flex justify-content-end align-items-center text-center">
                            @include('topic.inc.like', ['model' => $model])
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endforeach
