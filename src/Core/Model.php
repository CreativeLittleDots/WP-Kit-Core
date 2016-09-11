<?php
    
    namespace WPKit\Core;

	class Model {
		
		/* General entity id*/
		public $id;
		
		/* Original WP entity */
        protected $entity;
    	
    	/* General status, defaults to WP_Post standard */
    	protected $status = 'publish';
    	
    	/* Class Extension, defaults to WP_Post*/
		protected static $model = 'WP_Post';
    	
    	/* In case this is a WP_Post Layered Model, lets set the post type to null */
    	protected static $post_type;
    	
        /* General meta data*/
        protected static $meta = [];
        
    	
    	public function __construct( $entity = null ) {
	    	
        	if( $entity ) {
	        	
	        	$class = get_called_class();
	        	
	        	$model = $class::$model;
        	
	            $entity = $entity instanceOf $model ? $entity : $model::get_instance( $entity );

	            $this->entity = $entity;
	            
	            if( $model == 'WP_Post' ) {
		            
		            $this->id = $entity->ID;
		            $this->title = $entity->post_title;
					$this->status = $entity->post_status;
					$this->post_type = $class::getPostType();
		            
	            }
	            
	            $this->populate( $entity );
	            
			}
        	
    	}
    	
    	public static function getModel() {
	    	
	    	$class = get_called_class();
	    	
	    	return $class::$model;
	    	
    	}
    	
    	public static function getPostType() {
	    	
	    	$class = get_called_class();
	    	
	    	return $class::$post_type ? $class::$post_type : 'post';
	    	
    	}
    	
    	public function getMeta() {
	    	
	    	$meta_data = array();
	    	
	    	foreach($this->meta as $meta_key) {
		    	
		    	$meta_data[$meta_key] = $this->$meta_key;
		    	
	    	}
	    	
	    	return array_filter($meta_data);
	    	
    	}
    	
    	public function getEntity() {
	    	
	    	return $this->entity;
	    	
    	}
    	
    	public function getPostArgs($args = array()) {
	    	
	    	return array_merge(array(
		    	'post_title' => $this->title ? $this->title : null,
		    	'post_type' => $this->post_type ? $this->post_type : 'post',
		    	'meta_query' => array_map(function($key, $value) {
			    	return compact('key', 'value');
		    	}, array_keys($this->getMeta()), $this->getMeta())
	    	), $args);
	    	
    	}
    	
    	public function populate( $post ) {}
    	
    	public function findOrCreate($args = array()) {
	    	
	    	if( $this->model == 'WP_Post' ) {
	        
		        if( $posts = get_posts( $this->getPostArgs($args) ) ) {
			        
			        $model = get_called_class();
			        
			        $found = new $model( reset( $posts ) );
			        			        
			        $this->id = $found->id; 
			        
		        } else {
			        
			        foreach($args as $key => $val) {
			        
			        	$this->$key = $val;
			        
			        }
			        
		        }
		        
		        return $this->save();
		        
			}
	        
        }
        
        public function save() {
	        
	        if( $this->model == 'WP_Post' ) {
	        
		        if( $this->id ) {
			        
			        wp_update_post(array(
				        'ID' => $this->id,
				        'post_title' => $this->title,
				        'post_type' => $this->post_type,
				        'post_status' => $this->status,
			        ));
			        
		        } else {
			        
			    	$this->id = wp_insert_post(array(
				        'post_title' => $this->title,
				        'post_type' => $this->post_type,
				        'post_status' => $this->status,
			        )); 
			        
		        }
		        
		        foreach( $this->getMeta() as $meta_key => $meta_value ) {
			        
			        update_post_meta( $this->id, $meta_key, $meta_value );
			        
		        }
		        
		    }
		    
		    return $this;
	        
        }
		
	}

?>