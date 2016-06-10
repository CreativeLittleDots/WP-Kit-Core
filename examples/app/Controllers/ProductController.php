<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class ProductController extends Controller {
        
        public function __construct() {
           
           	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
			add_action( 'woocommerce_before_main_content', array($this, 'display_product_block') );
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );
			add_filter( 'woocommerce_product_description_heading', '__return_false' );

			            
        }
 		
		public function display_product_block() {
			
			global $post;
			
			$product = $GLOBALS['product'] = wc_get_product( $post );
			
			$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'post' );
			$image = $image[0];
			
			wc_get_template( 'single-product/introduction.php', compact('product', 'image') );
			
		}
		
	}