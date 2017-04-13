<?php

namespace WPKit\Providers;

use Illuminate\Routing\RoutingServiceProvider as ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    
    /**
     * Register the router instance.
     *
     * @return void
     */
    protected function registerRouter()
    {
        
        $this->app->instance(
            'invoker',
            $this->app->make('WPKit\Routing\Invoker', ['app' => $this->app])
        );
        
        add_action( 'template_redirect', function() {
	        
	        $this->app->send();
	        
        });
        
    }
    
}
