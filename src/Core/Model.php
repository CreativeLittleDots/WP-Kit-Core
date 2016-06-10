<?php
    
    namespace WPKit\Core;
    
    use WP_Post;

	class Model {
    	
        var $id;
        var $post;
    	
    	public function __construct( $post ) {
        	
            $post = $post instanceOf WP_Post ? $post : get_post( $post );
            
            $this->id = $post->ID;
            $this->post = $post;
            
            $this->populate( $post );
        	
    	}
    	
    	public function populate( $post ) {}
		
	}

?>