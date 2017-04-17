<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Support\ServiceProvider;
	
	class StoreServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
			
			$this->app->instance(
	            'store',
	            $this->app->make('WPKit\Store\Store')
	        );
	        
	    }
	
	}