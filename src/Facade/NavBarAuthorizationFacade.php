<?php namespace Empari\Support\Facade;

use Empari\Support\Http\Menu\Navbar;
use Illuminate\Support\Facades\Facade;

class NavBarAuthorizationFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return Navbar::class;
    }
}