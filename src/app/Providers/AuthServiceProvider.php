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
		    
		    $this->app['config']['auth.defaults.guard'] = 'default';
		    $this->app['config']['auth.providers.eloquent'] = [
			    'driver' => 'eloquent',
    			'model' => \WPKit\Models\User::class
    		];
			$this->app['config']['auth.guards.default'] = [
    			'driver' => 'session',
    			'provider' => 'eloquent'
    		];
		    
		    $this->app->instance(
	            'auth',
	            $this->app->make('Illuminate\Auth\AuthManager', ['app' => $this->app])
	        );
			
			$this->app->instance(
	            'auth.basic',
	            $this->app->make('Illuminate\Auth\Middleware\AuthenticateWithBasicAuth', ['auth' => $this->app['auth']])
	        );
	        
	    }
	
	}