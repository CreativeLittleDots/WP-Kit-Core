<?php
    
    namespace WPKit\Store;
    
    use Illuminate\Contracts\Cache\Store as StoreContract;

	class Store implements StoreContract {
		
		/**
	     * Set an item from the cache by key.
	     *
	     * @param  string  $key
	     * @param  mixed  $val
	     * @return mixed
	     */
		public function set( $key, $val ) {
    		
    		return $this->put( $key, $val, 3600 );
    		
		}
		
		/**
	     * Remove an item from the cache by key.
	     *
	     * @param string  $key
	     * @return void
	     */
		public function remove( $key ) {
			
			unset( $GLOBALS['wpkit__store'][$key] );
			
		}
		
		/**
	     * Retrieve an item from the cache by key.
	     *
	     * @param  string  $key
	     * @return mixed
	     */
	    public function get($key) {
    		
    		return ! empty( $GLOBALS['wpkit__store'][$key] ) ? $GLOBALS['wpkit__store'][$key] : false;
    		
		}
	
	    /**
	     * Retrieve multiple items from the cache by key.
	     *
	     * Items not found in the cache will have a null value.
	     *
	     * @param  array  $keys
	     * @return array
	     */
	    public function many(array $keys) {
		    
		    $values = [];
		    
		    foreach($keys as $key) {
			    
			    $values[] = $GLOBALS['wpkit__store'][$key];
			    
		    }
		    
		    return $values;
		    
	    }
	
	    /**
	     * Store an item in the cache for a given number of minutes.
	     *
	     * @param  string  $key
	     * @param  mixed   $value
	     * @param  float|int  $minutes
	     * @return void
	     */
	    public function put($key, $value, $minutes) {
    		
    		return $GLOBALS['wpkit__store'][$key] = $value;
    		
		}
	
	    /**
	     * Store multiple items in the cache for a given number of minutes.
	     *
	     * @param  array  $values
	     * @param  float|int  $minutes
	     * @return void
	     */
	    public function putMany(array $values, $minutes) {
		    
		    foreach($values as $key => $val) {
			    
			    $GLOBALS['wpkit__store'][$key] = $val;
			    
		    }
		    
	    }
	
	    /**
	     * Increment the value of an item in the cache.
	     *
	     * @param  string  $key
	     * @param  mixed   $value
	     * @return int|bool
	     */
	    public function increment($key, $value = 1) {
		    
		    return $GLOBALS['wpkit__store'][$key]+$value;
		    
	    }
	
	    /**
	     * Decrement the value of an item in the cache.
	     *
	     * @param  string  $key
	     * @param  mixed   $value
	     * @return int|bool
	     */
	    public function decrement($key, $value = 1) {
		    
		    return $GLOBALS['wpkit__store'][$key]-$value;
		    
	    }
	
	    /**
	     * Store an item in the cache indefinitely.
	     *
	     * @param  string  $key
	     * @param  mixed   $value
	     * @return void
	     */
	    public function forever($key, $value) {
		    
		    
		    
	    }
	
	    /**
	     * Remove an item from the cache.
	     *
	     * @param  string  $key
	     * @return bool
	     */
	    public function forget($key) {
		    
		    return $this->remove($key);
		    
	    }
	
	    /**
	     * Remove all items from the cache.
	     *
	     * @return bool
	     */
	    public function flush() {
		    
		    $GLOBALS['wpkit__store'] = [];
		    
	    }
	
	    /**
	     * Get the cache key prefix.
	     *
	     * @return string
	     */
	    public function getPrefix() {}
    	
    }