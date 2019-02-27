<?php

namespace App\Http\Middleware;

use Closure;

class ResponseMiddleware
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
        $response = $next($request);
        $content = $response->content();

        $url = url()->current();

        $pattern = '/&gt;&gt;([0-9]{1,4})/';
        $replace = '<a href="$1" id="$1">&gt;&gt;$1</a>';
        $content = preg_replace($pattern, $replace, $content);
        
        $response->setContent($content);
        return $response;
    }
}
