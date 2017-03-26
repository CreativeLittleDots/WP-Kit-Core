<?php

	namespace WPKit\Facades;
	
	use Illuminate\Support\Facades\Facade;

	class Invoker extends Facade {
		
	    /**
	     * Get the registered name of the component.
	     *
	     * @return string
	     */
	    protected static function getFacadeAccessor()
	    {
	        return 'invoker';
	    }
	    
	}