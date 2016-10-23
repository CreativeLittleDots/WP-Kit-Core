<?php
    
    namespace WPKit\Core;

	class Collection { ##deprecated
		
		public $model = '';
		public $args = array();
    	
    	public function __construct( $post = null ) {
	    	
	    	_deprecated_file( __FILE__, '1.3', null, "Please us relative ORM model");
	    	
        	$class = $this->model = stripos($this->model, '\\') === 0 ? $this->model : "App\Models\\{$this->model}";
        	
        	if( $class::getModel() == 'WP_Post' ) {
	        	
	        	$this->args['post_type'] = $class::getPostType();
	        	
        	}
        	
    	}
    	
    	public function where($args = array()) {
	    	
	    	$this->args = array_merge($this->args, $args);
	    	
	    	return $this;
	    	
    	}
    	
    	public function get() {
	    	
	    	$posts = get_posts($this->args);
	    	$model = $this->model;
	    	
	    	foreach($posts as &$post) {	
		    	
		    	$post = new $model( $post );
		    	
	    	}
	    	
	    	return $posts;
	    	
    	}
		
	}

?>