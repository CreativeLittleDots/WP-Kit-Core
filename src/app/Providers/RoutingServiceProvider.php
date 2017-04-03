<?php

namespace WPKit\Providers;

use Illuminate\Routing\RoutingServiceProvider as ServiceProvider;
use WPKit\Routing\Router;

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
        
        $this->app->instance(
            'invoker',
            $this->app->make('WPKit\Routing\Invoker', ['app' => $this->app])
        );
        
        add_action( 'init', function() {
	        
	        $this->app['router']->dispatch( $this->app['http'] );
	        
        });
        
    }
    
}
