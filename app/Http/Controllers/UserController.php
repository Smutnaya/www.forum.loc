<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Viewers\UserViewer;
use App\AppForum\Executors\UserExecutor;
use App\AppForum\Executors\ImagesExecutor;

class UserController extends Controller
{
    public function index($user_id)
    {
        $user = $this->user();

        $model = UserViewer::index($user_id, $user);

        return view('user.index', compact('model'));
    }

    public function role($user_id)
    {
        $user = $this->user();
        $result = UserExecutor::role($user_id, $user,  request()->all());

        if ($result['success']) {
            return redirect('user/' . $result['user_id'])->with(['messageCancel' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function image($user_id, Request $request)
    {
        if (!is_null($request->file('image'))) {
            $user = $this->user();
            $filenamewithextension = $request->file('image')->getClientOriginalName();


            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('image')->getClientOriginalExtension();
            //dd(imagecreatefromjpeg($request->file('image')));

            // $im = imagecreatefrompng($request->file('image'));
            // $size = min(imagesx($im), imagesy($im));
            // $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
            // if ($im2 !== FALSE) {
            //     imagepng($im2, 'example-cropped.png');
            //     imagedestroy($im2);
            // }
            // imagedestroy($im);

            $allowed_extension = array("jpg", "gif", "png", "jpeg", "bmp");
            //"jpg", "GPG", "gif", "GIF", "png", "PNG", "jpeg", "JPEG", "bmp", "BMP"
            if (in_array(mb_strtolower($extension), $allowed_extension) && filesize($request->file('upload')) < 2 * 1000000) {
                //filename to store

                //$filenametostore = $filename . '_' . time() . '.' . $extension;
                $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
                $filenametostore = 'av' . md5(substr(str_shuffle($permitted_chars), 0, 5) . time()) . '.' . $extension;

                $result = ImagesExecutor::avatar_post($user, $filenametostore, filesize($request->file('image')));

                if ($result['success']) {

                    //Upload File
                    $request->file('image')->storeAs('/public/uploads/avatars/', $filenametostore);

                    // Render HTML output
                    @header('Content-type: text/html; charset=utf-8');
                } else {
                    echo $result['message'];
                }
            } elseif (in_array(mb_strtolower($extension), $allowed_extension) && filesize($request->file('upload')) > 2000000) {
                echo 'Максимально допустимый размер файла 2мб';
            } else {
                echo 'Не удалось загрузить изображение! Допустимые форматы для загрузки: "jpg", "gif", "png", "jpeg", "bmp".';
            }

            if ($result['success']) {
                return redirect('user/' . $result['user_id']);
            }

            return redirect()->back()->withErrors(['message' => $result['message']]);
        } else {
            return redirect()->back()->withErrors(['message' => 'Изображение для загрузки не выбрано']);
        }
    }
}
