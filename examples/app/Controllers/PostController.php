<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class PostController extends Controller {
		
		public function beforeFilter() {
			
			add_action( 'after_post_archive_title', array($this, 'after_post_archive_title') );
			add_action( 'archive_detail_post_display_meta', array($this, 'archive_detail_post_display_meta') );
			add_action( 'archive_loop_post_display_meta', array($this, 'archive_loop_post_display_meta') );
			add_filter( 'the_content', array($this, 'ext_link_to_new_tab') );
			add_filter( 'the_excerpt', array($this, 'ext_link_to_new_tab') );
			
			parent::beforeFilter();
			
		}
		
		public function after_post_archive_title() {
		
			get_component('elements', 'post-archive-controls');
			
		}
		
		public function archive_detail_post_display_meta() {
			
			get_component('loop', 'post-detail-date-meta');
			
		}
		
		public function archive_loop_post_display_meta() {
			
			get_component('loop', 'post-loop-date-meta');
			
		}
	    
	    public function ext_link_to_new_tab($content) {
	        
	        return preg_replace_callback('/<a[^>]+/', array($this, 'ext_link_to_new_tab_callback'), $content);
	    }
	    
	    public function ext_link_to_new_tab_callback($matches) {
	        
	        $link = $matches[0];
	        $site_link = get_bloginfo('url');
	    
	        if (strpos($link, 'target') === false) {
	            
	            $link = preg_replace("%(href=\S(?!$site_link))%i", 'target="_blank" $1', $link);
	            
	        } elseif (preg_match("%href=\S(?!$site_link)%i", $link)) {
	            
	            $link = preg_replace('/rel=\S(?!_blank)\S*/i', 'target="_blank"', $link);
	        }
	        
	        return $link;
	    }
		
	}