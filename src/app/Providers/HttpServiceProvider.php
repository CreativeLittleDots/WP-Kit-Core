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
	        
	    }
	
	    /**
	     * Bootstrap the application services.
	     *
	     * @return void
	     */
	    public function boot()
	    {
	        
	        $this->app->handle( $this->app['http'] );
	        
	    }

	
	}