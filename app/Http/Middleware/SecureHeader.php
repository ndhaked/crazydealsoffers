<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHeader
{
    // Enumerate headers which you do not want in your application's responses.
    // Great starting point would be to go check out @Scott_Helme's:
    // https://securityheaders.com/
    private $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
    ];
    public function handle($request, Closure $next)
    {
        $this->removeUnwantedHeaders($this->unwantedHeaderList);
        $response = $next($request);
      
        $response->headers->set("Access-Control-Allow-Origin","*");
        return $response;
    }
    private function removeUnwantedHeaders($headerList)
    {
        foreach ($headerList as $header){
            header_remove($header);
        }
    }
}
