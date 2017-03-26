<?php

namespace WPKit\Routing;

use Illuminate\Routing\RoutingServiceProvider as ServiceProvider;
use WPKit\Core\Router;

class RoutingServiceProvider extends ServiceProvider
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
        
        add_action( 'init', function() {
	        
	        $this->app['router']->dispatch( $this->app['http'] );
	        
        });
        
    }
    
}
