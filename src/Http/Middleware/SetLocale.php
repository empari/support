<?php

namespace Empari\Support\Http\Middleware;

use Closure;
use Empari\Support\Exceptions\Localization\LanguageNotSupportedException;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws LanguageNotSupportedException
     */
    public function handle($request, Closure $next)
    {
        // read the language from the request header
        $locale = $request->header('Content-Language');

        // if the header is missed
        if(!$locale){
            // take the default local language
            $locale = app()->getLocale();
        }

        // check the languages defined is supported
        if (!array_key_exists($locale, config('app.supported_languages'))) {
            // respond with error
            throw new LanguageNotSupportedException();
        }

        // set the local language
        app()->setLocale($locale);

        // get the response after the request is done
        $response = $next($request);

        // set Content Languages header in the response
        $response->headers->set('Content-Language', $locale);

        // return the response
        return $response;
    }
}