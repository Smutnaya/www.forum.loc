<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CkeditorController extends Controller
{
    public function upload(Request $request)
    {

        //get filename with extension
        $filenamewithextension = $request->file('upload')->getClientOriginalName();

        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

        //get file extension
        $extension = $request->file('upload')->getClientOriginalExtension();

        $allowed_extension = array("jpg", "gif", "png", "jpeg", "bmp");
        //"jpg", "GPG", "gif", "GIF", "png", "PNG", "jpeg", "JPEG", "bmp", "BMP"
        if (in_array(mb_strtolower($extension), $allowed_extension)) {
            //filename to store
            $filenametostore = $filename . '_' . time() . '.' . $extension;

            //Upload File
            $request->file('upload')->storeAs('/public/uploads', $filenametostore);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('/storage/uploads/' . $filenametostore);

            $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url')</script>";

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        } else {
            echo 'Не удалось загрузить изображение! Допустимые форматы для загрузки: "jpg", "gif", "png", "jpeg", "bmp".';
        }
    }
}
