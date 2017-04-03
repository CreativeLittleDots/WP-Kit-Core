<?php

namespace WPKit\Providers;

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
        
        $this->app->instance(
            'frontEndNotifier',
            $this->app->make('WPKit\Notifications\Notifiers\FrontEndNotifier')
        );
        
        $this->app->instance(
            'adminNotifier',
            $this->app->make('WPKit\Notifications\Notifiers\AdminNotifier')
        );
        
    }
    
}
