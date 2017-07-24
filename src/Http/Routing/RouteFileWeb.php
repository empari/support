<?php

namespace Empari\Support\Http\Routing;

abstract class RouteFileWeb extends RouteFile
{
    /**
     * Register Routes
     */
    public function register()
    {
        $options = [
            'middleware' => 'web',
        ];

        $this->options = array_merge($this->options, $options);

        parent::register();
    }
}