<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Database\Capsule\Manager as Capsule;
	use Illuminate\Support\ServiceProvider;
	
	class WPKitServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
	        $this->registerEloquent();
	
	        $this->app->instance(
	            'env',
	            defined('WPKIT_ENV') ? WPKIT_ENV
	                : ( defined('WP_DEBUG') ? 'local'
	                    : 'production')
	        );
	
	        $this->app->instance(
	            'http',
	            \Illuminate\Http\Request::capture()
	        );
	        
	        $this->app->instance(
	            'invoker',
	            $this->app->make('WPKit\Routing\Invoker', ['app' => $this->app])
	        );
			
			$this->app->instance(
				'events',
				$this->app->make('WPKit\Events\Dispatcher', ['app' => $this->app])
			);
			
			$this->app->singleton(
			    \Illuminate\Contracts\Debug\ExceptionHandler::class,
			    \WPKit\Core\ExceptionHandler::class
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
	
	    /**
	     * Registers Eloquent.
	     *
	     * @return void
	     */
	    protected function registerEloquent()
	    {
	        global $wpdb;
	
	        $capsule = new Capsule($this->app);
	
	        $capsule->addConnection([
	            'driver' => 'mysql',
	            'host' => DB_HOST,
	            'database' => DB_NAME,
	            'username' => DB_USER,
	            'password' => DB_PASSWORD,
	            'charset' => DB_CHARSET,
	            'collation' => DB_COLLATE ?: $wpdb->collate,
	            'prefix' => $wpdb->prefix
	        ]);
	
	        $capsule->setAsGlobal();
	        $capsule->bootEloquent();
	    }
	
	    /**
	     * Boots the service provider.
	     *
	     * @return void
	     */
	    public function boot()
	    {
	        //$this->app['session']->start();
	    }
	
	}