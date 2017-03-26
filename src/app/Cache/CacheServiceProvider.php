<?php 
	
	namespace WPKit\Cache;

	use Illuminate\Support\ServiceProvider;
	
	class CacheServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
			
			$this->app->instance(
	            'cache',
	            $this->app->make('WPKit\Cache\Store')
	        );
	        
	    }
	
	}