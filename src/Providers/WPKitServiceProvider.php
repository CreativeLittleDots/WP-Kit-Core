<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Database\Capsule\Manager as Capsule;
	use Illuminate\Support\ServiceProvider;
	use Illuminate\Cookie\CookieJar;
	use WPKit\Core\Session;
	
	/**
	 * @see http://getherbert.com
	 */
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
	            \WPKit\Core\Http::capture()
	        );
	
	        $this->app->alias(
	            'http',
	            'WPKit\Core\Http'
	        );
	
	        $this->app->instance(
	            'router',
	            $this->app->make('WPKit\Core\Router', ['app' => $this->app])
	        );
	
	        $this->app->bind(
	            'route',
	            'WPKit\Core\Route'
	        );
	
	        $this->app->instance(
	            'session',
	            $this->app->make('WPKit\Core\Session', ['app' => $this->app])
	        );
	
	        $this->app->alias(
	            'session',
	            'WPKit\Core\Session'
	        );
	
	        $this->app->instance(
	            'notifier',
	            $this->app->make('WPKit\Core\Notifier', ['app' => $this->app])
	        );
	
	        $this->app->alias(
	            'notifier',
	            'WPKit\Core\Notifier'
	        );
	
	        $this->app->singleton(
	            'errors',
	            function ()
	            {
	                return session_flashed('__validation_errors', []);
	            }
	        );
	
	        $_GLOBALS['errors'] = $this->app['errors'];
	        
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
	        $this->app['session']->start();
	    }
	
	}