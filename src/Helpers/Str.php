<?php
	
	namespace WPKit\Helpers;

	use Illuminate\Support\Str as StrClass;

	class Str extends StrClass {
		
		/**
	     * Parse a Class@method style callback into class and method.
	     *
	     * @param  string  $callback
	     * @param  string|null  $default
	     * @return array
	     */
	    public static function parseCallback($callback, $default = null)
	    {
		    $default = $default ? $default : 'dispatch';
	        return static::contains($callback, '::') ? explode('::', $callback, 2) : parent::parseCallback( $callback, $default );
	    }
	    
	}