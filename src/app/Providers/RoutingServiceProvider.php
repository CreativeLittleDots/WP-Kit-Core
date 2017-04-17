<?php

namespace WPKit\Providers;

use Illuminate\Routing\RoutingServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        
        
        
        add_action( 'init', function() {
	        
	        Route::get('/{any}', function ($any) {
		
			  // any other url, subfolders also
			
			})->where('any', '.*');

	        $this->app->send();
	        
        });
        
    }
    
}
