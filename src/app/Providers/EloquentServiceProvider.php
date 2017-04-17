<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Database\Capsule\Manager as Capsule;
	use Illuminate\Support\ServiceProvider;
	
	class EloquentServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
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

	}