<?php
namespace Empari\Support\Http\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Empari\Units\Core\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        //$this->registerRouteClass(PhoneRoutes::class);
    }

    /**
     * Register Route with Class
     *
     * @param string $class
     * @return $this
     */
    protected function registerRouteClass(string $class)
    {
        (new $class([
            'namespace' => $this->namespace
        ]))->register();

        return $this;
    }
}