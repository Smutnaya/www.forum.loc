<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #f6f0cc !important;">
            <div class="modal-header py-1" style="border:none !important;">
                <span class="modal-title forum-desc" style="font-size: 14px !important;"
                    id="exampleModalLabel">Перемещение
                    темы</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- @dd($model) --}}
            <div class="modal-body p-2 pb-3 pe-3">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-center" >
                        <a
                            href="{{ url('/s/' . $model['forum']->section->id) }}">{{ $model['forum']->section->title }}</a>
                        &nbsp;/&nbsp;
                        <a href="{{ url('/f/' . $model['forum']->id) }}">{{ $model['forum']->title }}</a>
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col-12 d-flex justify-content-center">
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <i class="fa-solid fa-angles-down"></i>
                    </div>
                </div> --}}

                <div class="row mt-1" >
                    <div class="col-12 d-flex justify-content-center overflow-auto">
                        <form method='post' action='{{ url('/t/' . $model['topic']['id'] . '/move') }}'>
                            @csrf

                            @foreach ($model['section'] as $section)
                                <div class="col-12 mt-1 d-flex justify-content-center">
                                    <h6 class="fw-bold mb-01">{{ $section['title'] }}</h6>
                                </div>
                                @foreach ($section->forums as $forum)
                                    <div class="form-check p-0 new-tema">
                                        <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0"
                                            class="scrollspy-example" tabindex="0">
                                            <input type="radio" class="btn-check" name="check[]"
                                                id="{{ $forum['id'] }}" value="{{ $forum['id'] }}">
                                            <label class="btn btn-outline-primary p-0 " for="{{ $forum['id'] }}">
                                                <span class="d-sm-inline">{{ $forum['title'] }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                    </div>
                    <div class="col d-grid gap-2 d-flex justify-content-end mt-3">
                        <a data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-dark btn-custom my-0 py-0"
                            style="height: 21px !important">Отмена</a>
                        <input class="btn btn-sm btn-dark btn-custom my-0 py-0" style="height: 21px !important"
                            type="submit" value="Сохранить">
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
