<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Vistore;

class CountVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = hash('sha512', $request->ip());
    $browser = $request->header('User-Agent');
        if (Vistore::where('date', today())->where('ip', $ip)->where('browser',$browser)->count() < 1)
        {
          $vistore=  Vistore::create([
                'date' => today(),
                'ip' => $ip,
                'browser'=> $browser,
            ]);
            
        }
        return $next($request);
    }
}