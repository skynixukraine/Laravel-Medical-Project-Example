<?php

namespace App\Http\Middleware;

use Closure;

class SecurityMiddleware
{
    public $restrictIps = ['46.223.2.128',
                           '46.223.2.157',
                            '37.48.94.0'
                        

        ];

    public $restrictedAgentsPatterns = ['/curl/'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // the actual user IP is set in the header by the classic ELB
        // https://docs.aws.amazon.com/de_de/elasticloadbalancing/latest/classic/x-forwarded-headers.html
        $ip = $request->header('X-Forwarded-For');
        $agent = $request->header('user-agent');

        if ($ip && (in_array($ip, $this->restrictIps))) {
            \Log::info("blocked request by IP");
            return response(['error' => 'temporarily banned'], '429');
        }

        if ($agent) {
            foreach ($this->restrictedAgentsPatterns AS $restrictedAgent) {
                if (preg_match($restrictedAgent, $agent)) {
                    \Log::info("blocked request by User-Agent");
                    return response(['error' => 'invalid request'], '429');
                }
            }
        }

        return $next($request);
    }
}
