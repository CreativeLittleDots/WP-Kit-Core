<?php
    
    namespace WPKit\Core;

	class Model {
		
		/* General entity id */
		public $id;
		
		/* General entity title */
		public $title;
		
		/* General entity slug */
		public $slug;
		
		/* General entity content */
		public $content;
		
		/* General entity url */
		public $url;
		
		/* General entity blog id */
		public $blog_id;
		
		/* General entity thumbnail id */
		public $thumbnail_id;
		
		/* Original WP entity */
        protected $entity;
    	
    	/* General status, defaults to WP_Post standard */
    	protected $status = 'publish';
    	
    	/* Class Extension, defaults to WP_Post*/
		protected static $model = 'WP_Post';
    	
    	/* In case this is a WP_Post Layered Model, lets set the post type to null */
    	protected static $post_type = 'post';
    	
        /* General meta data*/
        protected static $meta = [];
        
        /* Check if is new model*/
        protected $is_new = false;
        
    	
    	public function __construct( $entity = null ) {
	    	
        	if( $entity ) {
	        	
	        	try {
	        	
		        	$model = self::getModel();
	        	
		            $entity = $entity instanceOf $model ? $entity : ( method_exists( $model, 'get_instance' ) ? $model::get_instance( $entity ) : new $model( $entity ) );
		            
		            if( $model == 'WP_Post' ) {
			            
			            if( $entity->post_type !== self::getPostType() ) {
				            
				            throw new \Exception( "This is an incorrect Post Type" );
				            
			            }
			            
		            }
		            
		            $this->entity = $entity;
		            
		            if( is_multisite() && $entity->blog_id ) {
				            
			            $current_blog_id = get_current_blog_id();
			            
			            switch_to_blog( $entity->blog_id );
			            
		            }
		            
		            $this->beforePopulate( $entity );
					
					if( is_multisite() && $entity->blog_id ) {
			            
			            switch_to_blog( $current_blog_id );
			            
		            }
		            
		            $this->populate( $entity );
		            
				} catch(\Exception $e) {
					
					wp_die( $e->getMessage() );
					
				}
	            
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
	    	
	    	$class = get_called_class();
	    	
	    	$meta_data = array();
	    	
	    	foreach($class::$meta as $meta_key) {
		    	
		    	$meta_data[$meta_key] = $this->$meta_key;
		    	
	    	}
	    	
	    	return array_filter($meta_data);
	    	
    	}
    	
    	public function getEntity() {
	    	
	    	return $this->entity;
	    	
    	}
    	
    	public function isNew() {
	    	
	    	return $this->is_new;
	    	
    	}
    	
    	public function getTitle() {
	    	
	    	if( self::getModel() == 'WP_Post' ) {
		            
	            return $this->title;
	            
            } 
	    	
    	}
    	
    	public function getContent() {
	    	
	    	if( self::getModel() == 'WP_Post' ) {
		    	
		    	return apply_filters( 'the_content', $this->content, $this->entity );
		    	
	    	}
	    	
    	}
    	
    	public function getUrl() {
	    	
	    	if( self::getModel() == 'WP_Post' ) {
		    	
		    	return $this->url;
		    	
	    	}
	    	
    	}
    	
    	public function getBlogId() {
	    	
	    	if( self::getModel() == 'WP_Post' ) {
	    	
	    		return $this->blog_id;
	    		
	    	}
	    	
    	}
    	
    	public function getAuthorId() {
	    	
	    	if( self::getModel() == 'WP_Post' ) {
	    	
	    		return $this->author_id;
	    		
	    	}
	    	
    	}
    	
    	public function getThumbnailId() {
	    	
	    	return $this->thumbnail_id;
	    	
    	}
    	
    	public function getThumbnailSrc( $size = 'full' ) {
	    	
	    	$image = wp_get_attachment_image_src( $this->getThumbnailId(), $size );
	    	
	    	return $image[0];
	    	
    	}
    	
    	public function getDateModified( $date = null ) {
	    	
	    	$date = $date ? $date : get_option( 'date_format' );
	    	
	    	return date( $date, strtotime( $this->date_modified ) );
	    	
    	}
    	
    	public function getComments( $args = array() ) {
	    	
	    	if( self::getModel() == 'WP_Post' ) {
    		
	    		$args = array_merge(array(
	    			'author_email' => '',
	    			'author__in' => '',
	    			'author__not_in' => '',
	    			'include_unapproved' => '',
	    			'fields' => '',
	    			'ID' => '',
	    			'comment__in' => '',
	    			'comment__not_in' => '',
	    			'karma' => '',
	    			'number' => '',
	    			'offset' => '',
	    			'orderby' => '',
	    			'order' => 'DESC',
	    			'parent' => '',
	    			'post_author__in' => '',
	    			'post_author__not_in' => '',
	    			'post_ID' => '', // ignored (use post_id instead)
	    			'post_id' => $this->id,
	    			'post__in' => '',
	    			'post__not_in' => '',
	    			'post_author' => '',
	    			'post_name' => '',
	    			'post_parent' => '',
	    			'post_status' => '',
	    			'post_type' => '',
	    			'status' => 'all',
	    			'type' => '',
	    			'user_id' => '',
	    			'search' => '',
	    			'count' => false,
	    			'meta_key' => '',
	    			'meta_value' => '',
	    			'meta_query' => '',
	    			'date_query' => null, // See WP_Date_Query
	    		), $args);
	    			
	    		return get_comments( $args );
	    		
	    	}
    		
    	}
    	
    	public function getPostArgs($args = array()) {
	    	
	    	return array_merge(array(
		    	'post_title' => $this->title ? $this->title : null,
		    	'post_type' => self::getPostType(),
		    	'meta_query' => array_map(function($key, $value) {
			    	return compact('key', 'value');
		    	}, array_keys($this->getMeta()), $this->getMeta())
	    	), $args);
	    	
    	}
    	
    	public function beforePopulate( $entity ) {
	    	
	    	if( self::getModel() == 'WP_Post' ) {
		    	
		    	$this->id = $entity->ID;
	            $this->title = $entity->post_title;
	            $this->slug = $entity->post_name;
	            $this->content = $entity->post_content;
	            $this->url = get_permalink( $entity->ID );
	            $this->blog_id = $entity->blog_id;
				$this->author_id = $entity->author;
				$this->thumbnail_id = get_post_thumbnail_id( $entity->ID );
				$this->date_modified = get_post_modified_time( 'Y-m-d', false, $entity->ID );
				$this->status = $entity->post_status;
		    	
		    } else if( self::getModel() == 'WP_Term' ) {
		    	
		    	$this->id = $entity->term_id;
	            $this->title = $entity->name;
	            $this->slug = $entity->slug;
	            $this->content = $entity->description;
	            $this->url = get_term_link( $entity->term_id );
	            $this->blog_id = $entity->blog_id;
				$this->thumbnail_id = get_term_meta( $entity->term_id, 'thumbnail_id', true );
		    	
		    }
	    	
    	}
    	
    	public function populate( $entity ) {}
    	
    	public function findOrCreate($args = array()) {
	    	
	    	if( self::getModel() == 'WP_Post' ) {
	        
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
	        
	        if( self::getModel() == 'WP_Post' ) {
	        
		        if( $this->id ) {
			        
			        wp_update_post(array(
				        'ID' => $this->id,
				        'post_title' => $this->title,
				        'post_status' => $this->status,
				        'post_content' => $this->content
			        ));
			        
		        } else {
			        
			    	$this->id = wp_insert_post(array(
				        'post_title' => $this->title,
				        'post_type' => self::getPostType(),
				        'post_status' => $this->status,
				        'post_content' => $this->content
			        )); 
			        
			        $this->is_new = true;
			        
		        }
		        
		        foreach( $this->getMeta() as $meta_key => $meta_value ) {
			        
			        update_post_meta( $this->id, $meta_key, $meta_value );
			        
		        }
		        
		        $this->beforePopulate( get_post( $this->id ) ); 
		        
		    }
		    
		    $this->afterSave();
		    
		    return $this->id;
	        
        }
        
        public function afterSave() {}
        
        public function delete() {
            
            $deleted = false;
            
            if( self::getModel() == 'WP_Post' ) {
                
                $deleted = wp_delete_post($this->id);
                
            }
        	
        	$this->afterDelete();
    
    		return $deleted;
    		
    	}
    	
    	public function afterDelete() {}
		
	}

?>