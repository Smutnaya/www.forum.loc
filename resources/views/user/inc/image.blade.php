<div style="display:none; font-size: 11px !important; background: #f9f5dc !important; border: 1px solid rgb(226 213 172 / 45%); border-radius: 0.5rem;" id="image">
    <div class="d-flex justify-content-center p-1 py-3 shadow-sm">
        <form action='{{ url('/u/' . $model['user_inf']['id'] . '/image') }}' method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" id="image" placeholder="image" name="image" accept="image/jpeg,image/png,image/gif,image/jpg,image/bmp">
            <div class="mt-1">
                <a onclick="toggleImageHide()" style="font-size: 13px !important; height: 22px !important;" class="btn btn-custom p-0 mt-1">Отмена</a>
                <input style="font-size: 13px !important; height: 22px !important;" type="submit" class="btn btn-custom p-0 mt-1" value="Загрузить">
            </div>
        </form>
    </div>
</div>
