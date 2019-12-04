<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SecureHeaders
{
    private $unwantedHeaders = [
        'X-Powered-By',
        'Server',  // does not work
    ];


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->removeUnwantedHeaders($this->unwantedHeaders);
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        // we cant set X-Frame-Options, because we can´t allow  https://online-hautarzt.net, https://intimarzt.de and online-dermatologist.net at the same time
        // but it is anyway replaced by Content-Security-Policy: frame-ancestors
        // $response->headers->set('X-Frame-Options', 'ALLOW-FROM http://fachportal.localhost/');
        $response->headers->set('Strict-Transport-Security', 'max-age=15768000; includeSubDomains'); // 6 months
        if (App::Environment() == "production") {
            $response->headers->set("Content-Security-Policy", ["frame-ancestors https://online-hautarzt.net https://intimarzt.de https://online-dermatologist.net",
                                                                "default-src data: blob: 'self' *.online-hautarzt.net https://js.stripe.com https://m.stripe.network 'unsafe-inline' 'unsafe-eval'"]);
        }
        elseif (App::Environment() == "staging") {
            $response->headers->set("Content-Security-Policy", ["frame-ancestors http://stajing.online-hautarzt.net",
                                                                "default-src data: blob: http://stajing.online-hautarzt.net https://api.online-hautarzt.net https://js.stripe.com https://m.stripe.network https://code.jquery.com/ https://maxcdn.bootstrapcdn.com/ 'unsafe-inline' 'unsafe-eval'"]);
        }
        else {
            $response->headers->set('Content-Security-Policy', ["frame-ancestors http://ohn.localhost",
                                                                "default-src data: blob: http://ohn.localhost http://aerzte.ohn.localhost https://js.stripe.com https://m.stripe.network https://code.jquery.com/ https://maxcdn.bootstrapcdn.com/ 'unsafe-inline' 'unsafe-eval'"]);
        }

        return $response;
    }

    private function removeUnwantedHeaders($headerList)
    {
        foreach ($headerList as $header)
            header_remove($header);
    }
}
