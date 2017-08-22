<?php
    
    namespace WPKit\Core;

	class PostType {
		
		/**
	     * The blog ids that post type should be registered on
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
	     * Does the post type should have a rewrite?
	     *
	     * @var boolean
	     */
    	var $rewrite = true;
    	
    	/**
	     * Does the post type have an archive?
	     *
	     * @var boolean
	     */
    	var $has_archive = true;
    	
    	/**
	     * The name of the post type to display in admin menu
	     *
	     * @var string
	     */
    	var $menu_name = '';
    	
    	/**
	     * The slug of the post type
	     *
	     * @var string
	     */
    	var $slug = '';
    	
    	/**
	     * The icon of the post type to display in admin menu
	     *
	     * @var string
	     */
    	var $icon = 'dashicons-admin-post';
    	
    	/**
	     * Is the post type hierarchical
	     *
	     * @var boolean
	     */
    	var $hierarchical = false;
    	
    	/**
	     * The label for post type to display in context of all items
	     *
	     * @var string
	     */
    	var $all_items = '';
    	
    	/**
	     * The label for post type to display when plural
	     *
	     * @var string
	     */
    	var $plural = '';
    	
    	/**
	     * The name of the post type
	     *
	     * @var string
	     */
    	var $name = '';
    	
    	/**
	     * Shoud the post type show in the admin menu?
	     *
	     * @var boolean
	     */
    	var $show_in_menu = true;
    	
    	/**
	     * Features that the post types supports
	     *
	     * @var array
	     */
    	var $supports = array();
    	
    	/**
	     * Actions to display on hover of row in wp admin
	     *
	     * @var array
	     */
    	var $row_actions = array();
    	
    	/**
	     * Is the post type public?
	     *
	     * @var boolean
	     */
    	var $public = true;
    	
    	/**
	     * Is the post type public queriable with WP_Query?
	     *
	     * @var boolean
	     */
    	var $publicly_queryable = true;
    	
    	/**
	     * Labels for post type
	     *
	     * @var array
	     */
    	var $labels = array();
		
		/**
	     * The constructor, it runs the whole registration for post type
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
			
			if ( $this->slug ) {
    			
    			$plural = inflector()->titleize($this->plural ? $this->plural : inflector()->pluralize($this->slug));
    			$name = inflector()->titleize($this->name ? $this->name : inflector()->humanize($this->slug));
		
				register_post_type( $this->slug , array(
					'labels'             => array_merge( array(
						'name'               => _x( $plural, 'post type general name', 'wpkit' ),
						'singular_name'      => _x( $name, 'post type singular name', 'wpkit' ),
						'menu_name'          => _x( $this->menu_name ? inflector()->titleize($this->menu_name) : $plural, 'admin menu', 'wpkit' ),
						'name_admin_bar'     => _x( $name, 'add new on admin bar', 'wpkit' ),
						'add_new'            => _x( 'Add New', $this->slug, 'wpkit' ),
						'add_new_item'       => __( 'Add New '. $name, 'wpkit' ),
						'new_item'           => __( 'New ' . $name, 'wpkit' ),
						'edit_item'          => __( 'Edit ' . $name, 'wpkit' ),
						'view_item'          => __( 'View ' . $name, 'wpkit' ),
						'all_items'          => __( $this->all_items ? inflector()->titleize( $this->all_items ) : 'All '. $plural, 'wpkit' ),
						'search_items'       => __( 'Search ' . $plural, 'wpkit' ),
						'parent_item_colon'  => __( 'Parent ' . $plural . ':', 'wpkit' ),
						'not_found'          => __( 'No ' . strtolower($plural) . ' found.', 'wpkit' ),
						'not_found_in_trash' => __( 'No ' . strtolower($plural) . ' found in Trash.', 'wpkit' )
					), $this->labels ),
					'public'             => $this->public,
					'publicly_queryable' => $this->publicly_queryable,
					'show_ui'            => true,
					'show_in_menu'       => $this->show_in_menu,
					'query_var'          => true,
					'rewrite'            => $this->rewrite && $this->rewrite !== true ? $this->rewrite : array( 'slug' => inflector()->dasherize( strtolower( $this->slug ) ) ),
					'capability_type'    => 'post',
					'has_archive'        => $this->has_archive && $this->has_archive !== true ? $this->has_archive : (  $this->has_archive ? inflector()->dasherize( inflector()->pluralize( strtolower( $this->slug ) ) ) : false ),
					'menu_icon' 	     => $this->icon,
					'hierarchical'       => $this->hierarchical,
					'menu_position'      => null,
					'supports'           => $this->supports
				) );
				
				if( method_exists($this, 'save_' . $this->slug ) ) {
					
					add_action('save_post', array($this, 'save_' . $this->slug) );
					
				}
				
				if( $this->row_actions ) {
					
					$actionType = $hierarchical ? 'page' : 'post';
					
					add_filter($actionType . '_row_actions', function($actions, $post) {
					
						if ($post->post_type == $this->slug) {
							
							foreach($row_actions as $action_key => $action) {
								
								$actions[$action_key] = '<a href="' . admin_url( 'post.php?post=' . $post->ID . '&action=' . $action_key ) . '">' . $action['name'] . '</a>';
								
							}
							
						}
						
						return $actions;
						
					}, 10, 2);
					
					add_action('admin_init', function() {
						
						if( isset($_REQUEST['post'] ) && isset( $_REQUEST['action'] ) && isset( $row_actions[$_REQUEST['action']] ) ) {
							
							if( isset( $row_actions[$_REQUEST['action']]['callback'] ) && $row_actions[$_REQUEST['action']]['callback'] && method_exists($this, $row_actions[$_REQUEST['action']]['callback']) ) {
    							
    							call_user_func(array($this, $row_actions[$_REQUEST['action']]['callback']));
    							
							}
								
						}
						
					});
					
				}
				
			}
			
        }
		
		
	}

?>
