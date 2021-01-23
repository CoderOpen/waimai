<?php
/**
 * 2019年11月29日 14:18
 */
namespace App\Http\Middleware\ChiHe;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

//// 注意，我们要继承的是 jwt 的 BaseMiddleware
class UserAuth extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if (!Auth::guard('chi_he')->check()) {
            return response()->json(['code' => 403, 'msg' => 'please login', 'data' => [],]);
        }
        $user  = Auth::guard('chi_he')->user();
        $request->offsetSet('user_id', $user->id);
        $request->offsetSet('open_id', $user->open_id);
        return $next($request);
    }
}
