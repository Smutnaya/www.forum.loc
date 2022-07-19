<?php

namespace App\Http\Middleware;

use Closure;
use App\Online;
use Illuminate\Support\Facades\Auth;
use App\AppForum\Executors\OnlineExecutor;

class CheckOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (auth()->user() && !is_null($user) && $request->method() == 'GET'){
            OnlineExecutor::post($request->path(),$user);
        }
        return $next($request);
    }
}

/* Димуль, [03.07.2022 22:19]
laravel get called contoller
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        dd($controller.'+'.$action);

Димуль, [03.07.2022 22:19]
dd($request->id);

Димуль, [03.07.2022 22:20]
$request->url();

Димуль, [03.07.2022 22:20]
$request->path();

Димуль, [03.07.2022 22:20]
$request->is('topic/*')

Димуль, [03.07.2022 22:21]
Auth::user();

Димуль, [03.07.2022 22:21]
$user = Auth::user();

Димуль, [03.07.2022 22:21]
if(!is_null($user) && is_null($user->online))
{
}
Димуль, [03.07.2022 22:22]
return $next($request);
 */

