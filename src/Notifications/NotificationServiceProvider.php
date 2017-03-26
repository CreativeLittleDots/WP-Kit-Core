<?php

namespace WPKit\Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    
    /**
     * Register the instance.
     *
     * @return void
     */
    public function register()
    {
	    
        $this->app->alias(
            'notifier',
            \WPKit\Notifications\Notifier::class
        );
        
    }
    
}
