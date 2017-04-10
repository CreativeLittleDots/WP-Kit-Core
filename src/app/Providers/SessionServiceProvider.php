<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Support\ServiceProvider;
	
	class SessionServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
		    
		    $this->app['session.store'] = null;
			
			$this->app->instance(
				'session',
				$this->app['http']->session()
			);
	        
	    }
	
	    /**
	     * Bootstrap the application services.
	     *
	     * @return void
	     */
	    public function boot()
	    {
	        
	        $this->app['session']->start();
	    }

	
	}