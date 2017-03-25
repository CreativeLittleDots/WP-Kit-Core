<?php

namespace WPKit\Facades;

use Illuminate\Support\Facades\Route as BaseRoute;

class Route extends BaseRoute {
	
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'router';
    }
    
}
