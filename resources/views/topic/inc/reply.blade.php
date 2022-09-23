{{-- forums --}}
@if ($model['section_id'] != 6)
    <div class="col d-flex justify-content-end align-items-center text-center p-0" style="font-size: 9pt;">
        <a class="quote" type="button" data-text="<div>{{ $post['text'] }}</div>" data-inf="<div class='mt-1 forum-desc text-end'><span class='fw-bold text-end'>{{ $post['user_post']['name'] }}</span>&nbsp;&middot;&nbsp;<span class='text-end'>{{ $post['date'] }}</span></div>">
            <i class="fa-solid fa-share me-1"><span class="fw-normal" style='font-family: "Open Sans", Arial, Helvetica, sans-serif !important;'>
                    Ответить</span></i>
        </a>
    </div>
@endif
