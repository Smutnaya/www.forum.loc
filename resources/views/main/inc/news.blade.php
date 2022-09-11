<div class="col border border-ligh shadow-sm overflow-auto news" style="background:#ffffdeb5; max-height: 630px; font-size: 10pt;">
    <div class="row py-1 fs-6  border-bottom" style="background:#f4efcd; position: sticky; top: 0px; border-bottom-color: #dee2e657 !important;">
        <div class="col fw-bold">
            Первая полоса
        </div>
    </div>
    @if ($model['news']->count() > 0)
        <?php $int = 1; ?>
        @foreach ($model['news'] as $news_post)
            @if ($int < 21)
                <section class="py-2 @if (!$loop->last) border-bottom" style="border-bottom-color: #e2d5ac !important; @endif">
                    <div class="row mb-1">
                        <div class="col text-break">
                            {{ $news_post['date'] }}
                            <span class="fw-bold post-a-color">
                                {{ $news_post['title'] }}
                            </span>
                        </div>
                    </div>
                    <div class="col text-break">
                        <?php
                        echo htmlspecialchars_decode($news_post['text']);
                        ?>
                    </div>
                </section>
            @endif
            <?php $int++; ?>
        @endforeach
    @else
        <div class="col py-3 fst-italic">
            Здесь пока пусто ;(
        </div>
    @endif
</div>
