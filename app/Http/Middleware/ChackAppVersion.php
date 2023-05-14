<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChackAppVersion
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
        $device_type = $request->headers->get('Platform');
        $reqVersion = $request->headers->get('Version');
        if ($device_type && $reqVersion) {
            if(auth()->user()){
                if(auth()->user()->userToken){
                    $token = 'Bearer '.auth()->user()->userToken->token;
                    if($token == $request->headers->get('authorization')){
                        return $next($request);
                    }else{
                       return response()->json(['status_code'=> \Config::get('custom.token-expire-code'),'message' => 'Token is Expired'], \Config::get('custom.token-expire-code'));
                    } 
                }else{
                     return response()->json(['status_code'=> \Config::get('custom.token-expire-code'),'message' => 'Authorization Token not found'], \Config::get('custom.token-expire-code'));
                }
            }
            return $next($request);
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Platform and version are required.', []);
        }
    }
}
