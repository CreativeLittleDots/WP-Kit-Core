<?php

namespace WPKit\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade as Facade;

class FacadeServiceProvider extends ServiceProvider
{
	
	/**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

	    Facade::setFacadeApplication($this->app);
	    
	}
    
}
