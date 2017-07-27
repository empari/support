<?php
namespace Empari\Http\Middleware;

use Closure;

/**
 * Class AlwaysExpectsJson
 * Add Request Headers Accept Application/Json
 *
 * @package Empari\Http\Middleware
 */
class AlwaysExpectsJson
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
        // set Content Languages header in the response
        $request->headers->add(['Accept' => 'application/json']);

        // return the response
        return $next($request);
    }
}