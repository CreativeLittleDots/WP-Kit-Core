<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Support\ServiceProvider;
	use Illuminate\Config\Repository as Config;
	use PDO;
	
	class WPKitServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
		    
		    $this->app->singleton('config', function () {
			    global $wpdb;
	            return new Config([
		            'app' => [
		                'providers' => [          
			                'WPKit\Providers\EloquentServiceProvider',
			                'WPKit\Providers\StoreServiceProvider',
			                'WPKit\Providers\TwigServiceProvider',
			                'WPKit\Providers\NotificationServiceProvider'
		                ]
		            ],
		            'session' => [
			            'path' => '/',
			            'domain' => get_site_url(),
			            'secure' => is_ssl(),
			            'driver' => 'file',
			            'lifetime' => 120,
			            'files' => $this->app->bootstrapPath('sessions'),
		            ],
		            'database' => [

					    /*
					    |--------------------------------------------------------------------------
					    | PDO Fetch Style
					    |--------------------------------------------------------------------------
					    |
					    | By default, database results will be returned as instances of the PHP
					    | stdClass object; however, you may desire to retrieve records in an
					    | array format for simplicity. Here you can tweak the fetch style.
					    |
					    */
					
					    'fetch' => PDO::FETCH_OBJ,
					
					    /*
					    |--------------------------------------------------------------------------
					    | Default Database Connection Name
					    |--------------------------------------------------------------------------
					    |
					    | Here you may specify which of the database connections below you wish
					    | to use as your default connection for all database work. Of course
					    | you may use many connections at once using the Database library.
					    |
					    */
					
					    'default' => 'mysql',
					
					    /*
					    |--------------------------------------------------------------------------
					    | Database Connections
					    |--------------------------------------------------------------------------
					    |
					    | Here are each of the database connections setup for your application.
					    | Of course, examples of configuring each database platform that is
					    | supported by Laravel is shown below to make development simple.
					    |
					    |
					    | All database work in Laravel is done through the PHP PDO facilities
					    | so make sure you have the driver for your particular database of
					    | choice installed on your machine before you begin development.
					    |
					    */
					
					    'connections' => [
					
					        'mysql' => [
					            'driver' => 'mysql',
					            'host' => DB_HOST,
					            'database' => DB_NAME,
					            'username' => DB_USER,
					            'password' => DB_PASSWORD,
					            'charset' => DB_CHARSET,
					            'collation' => DB_COLLATE ?: $wpdb->collate,
					            'prefix' => $wpdb->prefix,
					            'strict' => false,
					            'engine' => null,
					        ],
					
					    ],
					
					    /*
					    |--------------------------------------------------------------------------
					    | Migration Repository Table
					    |--------------------------------------------------------------------------
					    |
					    | This table keeps track of all the migrations that have already run for
					    | your application. Using this information, we can determine which of
					    | the migrations on disk haven't actually been run in the database.
					    |
					    */
					
					    'migrations' => 'migrations'
					
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
			    \Illuminate\Contracts\Http\Kernel::class,
			    \WPKit\Http\Kernel::class
			);
			
			$this->app->singleton(
			    \Illuminate\Contracts\Cache\Repository::class,
			    \Illuminate\Cache\Repository::class
			);
			
			$this->app->singleton(
			    \Illuminate\Contracts\Cache\Store::class,
			    \WPKit\Store\Store::class
			);
			
			$this->app->singleton(
			    \Illuminate\Contracts\Events\Dispatcher::class,
			    \Illuminate\Events\Dispatcher::class
			);
			
			$this->app->singleton(
			    \Illuminate\Contracts\Debug\ExceptionHandler::class,
			    \WPKit\Exceptions\Handler::class
			);
	        
	    }

	
	}