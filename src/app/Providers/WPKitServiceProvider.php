<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Support\ServiceProvider;
	use Illuminate\Config\Repository as Config;
	
	class WPKitServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
		    
		    $this->app->singleton('config', function () {
	            return new Config([
		            'app' => [
		                'providers' => []
		            ],
		            'session' => [
			            'driver' => 'file',
			            'lifetime' => 120,
			            'files' => $this->app->bootstrapPath('sessions'),
		            ]
		        ]);
	        });
	
	        $this->app->instance(
	            'env',
	            defined('WPKIT_ENV') ? WPKIT_ENV
	                : ( defined('WP_DEBUG') ? 'local'
	                    : 'production')
	        );
			
			$this->app->singleton(
			    \Illuminate\Contracts\Events\Dispatcher::class,
			    \Illuminate\Events\Dispatcher::class
			);
			
			$this->app->singleton(
			    \Illuminate\Contracts\Http\Kernel::class,
			    \WPKit\Http\Kernel::class
			);
			
			$this->app->singleton(
			    \Illuminate\Contracts\Debug\ExceptionHandler::class,
			    \WPKit\Exceptions\Handler::class
			);
	        
	    }

	
	}