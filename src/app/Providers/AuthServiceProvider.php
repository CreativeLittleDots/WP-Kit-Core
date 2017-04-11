<?php 
	
	namespace WPKit\Providers;

	use WPKit\Auth\AuthManager;
	use WPKit\Http\Middleware\FormAuth;
	use WPKit\Http\Middleware\OauthAuth;
	use Illuminate\Auth\AuthServiceProvider as BaseAuthServiceProvider;
	use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
	
	class AuthServiceProvider extends BaseAuthServiceProvider {
	
	    public function registerAuthenticator()
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
		    $this->app['config']['auth.guards.api'] = [
		        'driver' => 'passport',
		        'provider' => 'users',
		    ];
		    
		    $this->app->singleton('auth', function ($app) {
	            // Once the authentication service has actually been requested by the developer
	            // we will set a variable in the application indicating such. This helps us
	            // know that we need to set any queued cookies in the after event later.
	            $app['auth.loaded'] = true;
	
	            return new AuthManager($app);
	        });
	
	        $this->app->singleton('auth.driver', function ($app) {
	            return $app['auth']->guard();
	        });
        
			$this->app->singleton(
	            'auth:basic',
	            function($app) {
		            
		            return new AuthenticateWithBasicAuth($app['auth']);
		            
	            }
	        );
	        
	        $this->app->singleton(
	            'auth:form',
	            function($app) {
		            
		            return new FormAuth($app['auth']);
		            
	            }
	        );
	        
	    }

	
	}