<?php
    
    namespace WPKit\Core;
    
    class Route extends Singleton {
	    
	    var $path;
	    var $callback;
	    var $method;
	    
	    public function __construct($route) {
		    
		    $this->path = $route[0];
		    $this->callback = $route[1];
		    $this->method = $route[2];
		    
	    }
	    
    }