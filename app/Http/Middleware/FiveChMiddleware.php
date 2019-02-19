<?php

namespace App\Http\Middleware;

use Closure;

class FiveChMiddleware
{
    public function handle($request, Closure $next)
    {
        //プロクシか判定
        if(isset($_SERVER['HTTP_VIA']) || isset($_SERVER['CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            exit("Do Not proxy");
        }
        
        $agent = $request->server('HTTP_USER_AGENT');

        if ((preg_match('/Chrome/i', $agent) === 0) &&
            (preg_match('/Firefox/i', $agent) === 0) &&
            (preg_match('/Safari/i', $agent) === 0))
        {
            $request->merge($this->encodeString($request->input()));
            $response = $next($request);
        } else {
            $response = $next($request);
        }

        return $response;
    }

    private function encodeString($str, $to = 'UTF-8', $from = 'SJIS-win') {
        if (is_array($str))
        {
            $result = array();
            foreach ($str as $key => $value)
            {
                if (is_array($value))
                {
                    $result[$key] = $this->encodeRequest($value, $to, $from);
                }
                else
                {
                    $result[$key] = mb_convert_encoding($value, $to, $from);
                }
            }
            return $result;
        }
        else
        {
            return (mb_convert_encoding($str, $to, $from));
        }
    }
}
