<?php

namespace Empari\Support\Http\Routing;

abstract class RouteFileApi extends RouteFile
{
    /**
     * Register Routes
     */
    public function register()
    {
        $options = [
            'prefix' => !is_null(config('app.api_version')) ? config('app.api_version') : 'api',
            'middleware' => 'api',
        ];

        $this->options = array_merge($this->options, $options);

        parent::register();
    }
}