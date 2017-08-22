<?php
    
    namespace WPKit\Core;

	class Taxonomy {
		
		/**
	     * The blog ids that taxonomy should be registered on
	     *
	     * @var array
	     */
		var $blog_ids = [];
		
		/**
	     * The blog ids to excluded registration on
	     *
	     * @var array
	     */
		var $exclude_blog_ids = [];
		
		/**
	     * Does the taxonomy should have a rewrite?
	     *
	     * @var boolean
	     */
    	var $rewrite = true;
    	
    	/**
	     * The slug of the taxonomy
	     *
	     * @var string
	     */
    	var $slug = '';
    	
    	/**
	     * Is the taxonomy hierarchical
	     *
	     * @var boolean
	     */
    	var $hierarchical = false;
    	
    	/**
	     * The label for taxonomy to display when plural
	     *
	     * @var string
	     */
    	var $plural = '';
    	
    	/**
	     * The name of the taxonomy
	     *
	     * @var string
	     */
    	var $name = '';
    	
    	/**
	     * Where to nest the taxonomy page in wp admin
	     *
	     * @var array
	     */
    	var $belongs_to = array();
    	
    	/**
	     * Post types to register taxonomy on
	     *
	     * @var array
	     */
    	var $post_types = array();
    	
    	/**
	     * Labels for taxonomy
	     *
	     * @var array
	     */
    	var $labels = array();
		
		/**
	     * The constructor, it runs the whole registration for taxonomy
	     *
	     * @return void
		 */
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
