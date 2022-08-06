<div style="display:none; background: #f6f0cc;" class="borderborder border-ligh shadow-sm p-2 pt-0" id="topic-move-field">
    <span class="text-secondary mb-4" style="font-size: 13px">Перемещение темы</span>
    <div class="row ">
        <div class="col-lg-12 align-self-start">
            <form method='post' action='{{ url('/t/' . $model['topic']['id'] . '/move') }}'>
                @csrf
                <div class="row d-flex justify-content-center ">
                    @if (!is_null($model['section']))
                        @foreach ($model['section'] as $section)
                            <div class="col-xl-3 col-lg-4 col-md-12  scroll">
                                <h6 class="fw-bold my-1">{{ $section['title'] }}</h6>
                                <div class="overflow-auto" style="max-height: 125px;" id="myDiv">
                                    @if ($model['user']['role_id'] == 8)
                                        @foreach ($section->forums as $forum)
                                            @if ($forum['id'] != 16 && $forum['id'] != 17 && $forum['id'] != 69)
                                                <div class="form-check p-0 new-tema">
                                                    <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" class="scrollspy-example" tabindex="0">
                                                        <input type="radio" class="btn-check" name="check[]" id="{{ $forum['id'] }}" value="{{ $forum['id'] }}">
                                                        <label class="btn btn-outline-primary p-0 " for="{{ $forum['id'] }}">
                                                            <span class="d-sm-inline">{{ $forum['title'] }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @elseif ($model['user']['role_id'] > 8)
                                        @foreach ($section->forums as $forum)
                                            <div class="form-check p-0 new-tema">
                                                <div data-bs-spy="scroll" data-bs-offset="0" class="scrollspy-example" tabindex="0">
                                                    <input type="radio" class="btn-check" name="check[]" id="{{ $forum['id'] }}" value="{{ $forum['id'] }}">
                                                    <label class="btn btn-outline-primary p-0 " for="{{ $forum['id'] }}">
                                                        <span class="d-sm-inline">{{ $forum['title'] }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach ($section->forums as $forum)
                                            @if ($forum['id'] < 16 || $forum['id'] > 17)
                                                <div class="form-check p-0 new-tema">
                                                    <div data-bs-spy="scroll" data-bs-offset="0" class="scrollspy-example" tabindex="0">
                                                        <input type="radio" class="btn-check" name="check[]" id="{{ $forum['id'] }}" value="{{ $forum['id'] }}">
                                                        <label class="btn btn-outline-primary p-0 " for="{{ $forum['id'] }}">
                                                            <span class="d-sm-inline">{{ $forum['title'] }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <span class="d-flex justify-content-center text_centre text-danger">Отсутвуют права для
                            перемещения темы!</span>
                    @endif

                    <div class="row d-flex justify-content-center text_centre mt-2">
                        <a id="topic-move-hide" data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-dark btn-custom mt-2 mb-0 py-0 me-2" style="height: 21px !important">Отмена</a>
                        <input class="btn btn-sm btn-dark btn-custom ms-2 mt-2 mb-0 py-0" style="height: 21px !important" type="submit" value="Сохранить">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
