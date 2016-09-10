<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	use WP_Query;
	
	class AjaxController extends Controller {
	    
	    public function beforeFilter() {
			
			wpkit()->ajax( 'search_courses', array($this, 'search_courses') );
	        
	    }
		
		public function search_courses() {
	
			$args = wp_parse_args($_POST['data'], array(
				'post_type' => 'product',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'meta_query' => array(
					array(
						'key' => '_visibility',
						'value' => 'hidden',
						'compare' => '!='
					)
				)
			));
			
			wp_send_json(
				array(
					'response' => 'success', 
					'html' => wc_get_template_html( 'search-courses.php', array(
						'products' => new WP_Query($args), 
						's' => $args['s']
					) ),
					'args' => $args,
				)
			);
			
		}
		
	}