<?php
    
    namespace WPKit\Core;

	class Taxonomy { // to be deprecated 2.0
		
		var $blog_ids = [];
		var $exclude_blog_ids = [];
    	var $rewrite = true;
    	var $slug = '';
    	var $hierarchical = false;
    	var $plural = '';
    	var $name = '';
    	var $belongs_to = array();
    	var $post_types = array();
    	var $labels = array();
		
		public function __construct() {
    		
    		if( $this->blog_ids && ! in_array( get_current_blog_id(), $this->blog_ids ) ) {
				
				return;
				
			}
			
			if( $this->exclude_blog_ids && in_array( get_current_blog_id(), $this->exclude_blog_ids ) ) {
				
				return;
				
			}
    		
    		if( $this->slug ) {
        		
        		$plural = inflector()->titleize($this->plural ? $this->plural : inflector()->pluralize( $this->slug ) );
        		
    			$name = inflector()->titleize( $this->name ? $this->name : inflector()->humanize( $this->slug ) );
    			
    			register_taxonomy(
    				$this->slug,
    				$this->post_types,
    				array(
	    				'label' 			=> __( $name ),
	    				'hierarchical'      => $this->hierarchical,
	    				'labels'            =>  array_merge( array(
		    				'name'              => _x( $plural, 'wpkit' ),
		    				'singular_name'     => _x( $name, 'wpkit' ),
		    				'search_items'      => __( 'Search '.$plural ),
		    				'all_items'         => __( 'All ' . $plural ),
		    				'parent_item'       => __( 'Parent ' . $name ),
		    				'parent_item_colon' => __( 'Parent ' . $name.':' ),
		    				'edit_item'         => __( 'Edit ' . $name ),
		    				'update_item'       => __( 'Update ' . $name ),
		    				'add_new_item'      => __( 'Add New ' . $name ),
		    				'new_item_name'     => __( 'New ' . $name . ' Name' ),
		    				'menu_name'         => __( $plural ),
		    			), $this->labels ),
	    				'show_ui'           => true,
	    				'show_admin_column' => true,
	    				'query_var'         => true,
	    				'rewrite'            => $this->rewrite && $this->rewrite !== true ? $this->rewrite : array( 'slug' => inflector()->dasherize( strtolower( $this->slug ) ) ),
	    			)
    			);
    			
    			if( $this->belongs_to ) {
    			
    				add_action('admin_menu', function() use( $plural ) {
    					
    					add_submenu_page( 'edit.php?post_type=' . $this->belongs_to, $plural, $plural, 'edit_others_posts', 'edit-tags.php?taxonomy=' . $this->slug . '&post_type=' . $this->belongs_to) ;
    					
    				});	
    				
    			}
        		
    		}
			
		}
		
		
	}

?>
