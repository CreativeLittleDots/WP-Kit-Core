<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Support\ServiceProvider;
	
	class HttpServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
			
			$this->app->instance(
	            'http',
	            \Illuminate\Http\Request::capture()
	        );
	        
	        $this->app->instance(
	            'request',
	            \Illuminate\Http\Request::capture()
	        );
	        
	    }
	
	}