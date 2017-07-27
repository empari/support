<?php
namespace Empari\Support\Http\Middleware;

use Closure;

/**
 * Class AttachApiVersionResponse
 *
 * @package Empari\Http\Middleware
 */
class AttachApiVersionResponse
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

        // set Content Version header in the response
        $response->headers->add(['Content-Name' => config('app.name')]);
        $response->headers->add(['Content-Version' => config('app.version')]);

        // return the response
        return $response;
    }
}