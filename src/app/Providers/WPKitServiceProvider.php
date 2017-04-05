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
			
			$this->app->instance(
	            'http',
	            \Illuminate\Http\Request::capture()
	        );
			
			$this->app->instance(
				'events',
				$this->app->make('WPKit\Events\Dispatcher')
			);
			
			$this->app['http']->setSession( $this->app->make( 'Symfony\Component\HttpFoundation\Session\Session' ) );
			
			$this->app->instance(
				'session',
				$this->app['http']->session()
			);
			
			$this->app['config']['app.providers'] = ! empty( $this->app['config']['app.providers'] ) ? $this->app['config']['app.providers'] : [];
	        
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
	     * Bootstrap the application services.
	     *
	     * @return void
	     */
	    public function boot()
	    {
	        
	        $this->app['session']->start();
	        
	        $this->app->handle( $this->app['http'] );
	    }

	
	}