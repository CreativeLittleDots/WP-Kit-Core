<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Support\ServiceProvider;
	use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
	
	class AuthServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
		    
		    $this->app['config']['auth.defaults.guard'] = 'default';
		    $this->app['config']['auth.providers.eloquent'] = [
			    'driver' => 'eloquent',
    			'model' => \WPKit\Models\User::class
    		];
			$this->app['config']['auth.guards.default'] = [
    			'driver' => 'session',
    			'provider' => 'eloquent'
    		];
		    
			$this->app->singleton(
	            'auth.basic',
	            function($app) {
		            
		            return new AuthenticateWithBasicAuth($app['auth']);
		            
	            }
	        );
	        
	    }

	
	}