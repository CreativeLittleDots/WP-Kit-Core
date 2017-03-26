<?php

namespace WPKit\Providers;

use Illuminate\Routing\RoutingServiceProvider;
use WPKit\Core\Router;

class RouteServiceProvider extends RoutingServiceProvider
{
    
    /**
     * Register the router instance.
     *
     * @return void
     */
    protected function registerRouter()
    {
        $this->app['router'] = $this->app->share(function ($app) {
            return new Router($app['events'], $app);
        });
    }
    
}
