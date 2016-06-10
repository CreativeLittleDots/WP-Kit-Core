<?php
    
    namespace WPKit\Core;

	class Taxonomy {
		
    	var $rewrite = true;
    	var $slug = '';
    	var $hierarchical = false;
    	var $plural = '';
    	var $name = '';
    	var $belongs_to = array();
    	var $post_types = array();
    	
    	public static function init() {
	    	
	    	$class = get_called_class();
	    	
	    	return new $class();
	    	
    	}
		
		public function __construct() {
    		
    		if( $this->slug ) {
        		
        		$plural = inflector()->titleize($this->plural ? $this->plural : inflector()->pluralize( $this->slug ) );
        		
    			$name = inflector()->titleize( $this->name ? $this->name : inflector()->humanize( $this->slug ) );
    			
    			register_taxonomy(
    				$this->slug,
    				$this->post_types,
    				array(
	    				'label' 			=> __( $name ),
	    				'hierarchical'      => $this->hierarchical,
	    				'labels'            =>  array(
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
		    			),
	    				'show_ui'           => true,
	    				'show_admin_column' => true,
	    				'query_var'         => true,
	    				'rewrite'           => $this->rewrite ? $this->rewrite : array( 'slug' => $this->slug ),
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