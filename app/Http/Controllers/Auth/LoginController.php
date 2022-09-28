<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserGame;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\AppForum\Helpers\FilterHelper;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        $request = request();

        $request->validate([
            'title' => 'required|string',
            'password' => 'required|string',
            //'remember_me' => 'boolean'
        ]);

        /*
                $usergame = UserGame::where('title', 'Димитар')->first();

        //dd($usergame->password.' | '.Hash::make('qqqqqq'));

        dd(Hash::check('qqqqqq', $usergame->password));
        */
        
        $title = FilterHelper::sanitize($request->title);
        $password = FilterHelper::sanitize($request->password);

        $usergame = UserGame::where('title', $title)->first(); // estj li takoj igrok v db igri
        // TODO dimka: zdesj was mozno brutforcit na avtorizaciju (nado zapretit kak-to)
        if(is_null($usergame))
        {
            return redirect()->back()->withErrors([
                'title' => 'Персонаж с такими данными не найден'
            ]);
        }
        else
        {
            // estj proverim password APP_KEY game i foruma dolzni sovpadatj
            if(Hash::check($password, $usergame->password))
            {

            }
            else
            {
                return redirect()->back()->withErrors([
                    'title' => 'Персонаж с такими данными не найден'
                ]);
            }
        }

        $user = User::where('name', $title)->first();
        if(is_null($user)) // user ewe ne razu ne proboval zaxodit na forum i ego dannix net skopirovanix s bazi igri na forum
        {
            $user = User::create([
                'id' => $usergame->id,
                'name' => $usergame->title,
                'email' => $usergame->title.'@'.time(),
                'password' => 'no-password',
            ]);
            $user->save(); // na vsiakij sluchaj
        }

        // obnovim dannie iz igri
        $user->level = $usergame->level;
        $user->clan_id = $usergame->clan_id;
        $user->clan_role = $usergame->clan_rang;
        $user->alliance_id = $usergame->alliance_id;
        $user->save();

        # auth

        if(Auth::check()) Auth::logout();
        Auth::login($user);

        $user = Auth::user();
        if(!is_null($user)) return redirect()->route('main');

        // if(Auth::attempt(['name' => $user->title]))
        // {
        //     $user = Auth::user();
        //     if(!is_null($user)) return redirect()->route('main');

        //     return redirect()->back()->withErrors([ // error logged in executor
        //         'title' => 'Неизвестная ошибка авторизации! Пожалуйста, свяжитесь с администрацией'
        //     ]);
        // }

        return redirect()->back()->withErrors([
            'title' => 'Персонаж с такими данными не найден'
        ]);
    }
}
