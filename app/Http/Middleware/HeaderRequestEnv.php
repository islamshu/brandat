<?php

namespace App\Http\Middleware;

use Closure;

class HeaderRequestEnv
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
        $data = null;
        if (request()->header('X-Client-FCM-Token'))
            $data['fcm_token'] = request()->header('X-Client-FCM-Token');
        if (request()->header('x-client-fcm-token'))
            $data['fcm_token'] = request()->header('X-Client-FCM-Token');
        $user = auth('api')->user();
        if ($user && $data != null)
            $user->update($data);
        return $next($request);
    }
}
