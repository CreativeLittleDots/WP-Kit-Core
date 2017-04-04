<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Support\ServiceProvider;
	
	class AuthServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
		    
		    $this->app->instance(
	            'auth',
	            $this->app->make('Illuminate\Auth\AuthManager', ['app' => $this->app])
	        );
			
			$this->app['auth']->setDefaultDriver( '\WPKit\Auth\SessionGuard' );
	        
	    }
	
	}