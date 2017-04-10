<?php 
	
	namespace WPKit\Providers;

	use Illuminate\Support\ServiceProvider;
	
	class EventsServiceProvider extends ServiceProvider {
	
	    /**
	     * Register the service provider.
	     *
	     * @return void
	     */
	    public function register()
	    {
			
			$this->app->instance(
				'events',
				$this->app->make('WPKit\Events\Dispatcher')
			);
	        
	    }

	
	}