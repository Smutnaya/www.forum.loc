<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Executors\ImagesExecutor;

class CkeditorController extends Controller
{
    public function upload(Request $request)
    {
        $user = $this->user();
        //get filename with extension
        $filenamewithextension = $request->file('upload')->getClientOriginalName();

        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

        //get file extension
        $extension = $request->file('upload')->getClientOriginalExtension();

        $allowed_extension = array("jpg", "gif", "png", "jpeg", "bmp");
        //"jpg", "GPG", "gif", "GIF", "png", "PNG", "jpeg", "JPEG", "bmp", "BMP"
        if (in_array(mb_strtolower($extension), $allowed_extension) && filesize($request->file('upload')) < 2 * 1048576) {
            //filename to store

            //$filenametostore = $filename . '_' . time() . '.' . $extension;
            $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
            $filenametostore = md5(substr(str_shuffle($permitted_chars), 0, 5) . time()) . '.' . $extension;

            $result = ImagesExecutor::images_post($user, $filenametostore, filesize($request->file('upload')));

            if ($result['success']) {

                //Upload File
                $request->file('upload')->storeAs('/public/uploads/', $filenametostore);

                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $url = asset('/storage/uploads/' . $filenametostore);
                $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url')</script>";

                // Render HTML output
                @header('Content-type: text/html; charset=utf-8');
                echo $re;
            } else {
                echo $result['message'];
            }
        } elseif (in_array(mb_strtolower($extension), $allowed_extension) && filesize($request->file('upload')) > 2 * 1048576) {
            echo 'Максимально допустимый размер файла 2мб';
        } else {
            echo 'Не удалось загрузить изображение! Допустимые форматы для загрузки: "jpg", "gif", "png", "jpeg", "bmp".';
        }
    }

    private static function limit($filesize, $user)
    {
    }
}
