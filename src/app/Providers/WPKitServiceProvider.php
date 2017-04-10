<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Support\ServiceProvider;
	
	class WPKitServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
	
	        $this->app->instance(
	            'env',
	            defined('WPKIT_ENV') ? WPKIT_ENV
	                : ( defined('WP_DEBUG') ? 'local'
	                    : 'production')
	        );
			
			$this->app->singleton(
			    \Illuminate\Contracts\Events\Dispatcher::class,
			    \WPKit\Events\Dispatcher::class
			);
			
			$this->app->singleton(
			    \Illuminate\Contracts\Http\Kernel::class,
			    \WPKit\Http\Kernel::class
			);
			
			$this->app->singleton(
			    \Illuminate\Contracts\Console\Kernel::class,
			    \WPKit\Console\Kernel::class
			);
			
			$this->app->singleton(
			    \Illuminate\Contracts\Debug\ExceptionHandler::class,
			    \WPKit\Exceptions\Handler::class
			);
	        
	    }

	
	}