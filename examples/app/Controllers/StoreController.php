<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	use WP_Query;
	
	class StoreController extends Controller {
        
        public function __construct() {
           
          	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
			add_action( 'woocommerce_before_main_content', array($this, 'display_featured_products'), 20 );
			add_filter( 'woocommerce_show_page_title', '__return_false' );
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );
			remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );
			remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices' );
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );
			            
        }
 		
		public function display_featured_products() {
			
			$featured = new WP_Query( array(
				'post_type' => 'product', 
				'product_cat' => 'featured', 
				'posts_per_page'=> -1, 
				'order' => 'ASC', 
				'orderby' => 'menu_order' 
			) );
			
			wc_get_template( 'featured-products.php', compact('featured') );
			
			wp_reset_query();
			
		}
		
	}