<?php
    
    namespace WPKit\Core;

	class Cache {
		
		public static $vars;
		
		public static function set( $key, $val ) {
    		
    		return self::$vars[$key] = $val;
    		
		}
		
		public static function get( $key ) {
    		
    		return ! empty( self::$vars[$key] ) ? self::$vars[$key] : false;
    		
		}
		
		public static function remove( $key ) {
			
			unset( self::$vars[$key] );
			
		}
    	
    }