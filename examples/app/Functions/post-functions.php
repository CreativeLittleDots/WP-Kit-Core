<?php
    
    function get_post_search_link() {
        
        global $post;
        
        if( $type === 'event' && has_term( 'rsvp', 'event_category' ) ) {
    	
    	    $link = get_permalink( get_page_by_title( 'Photos' ) ) . '#' . $post->post_name;
    	
    	} else if ( get_field('document_link') ) {
    	
    	    $link = get_field('document_link') . '" target="_blank';
    	
    	} else if( $type === 'person' ) {
    	
    	    $departments = wp_get_object_terms($post->ID, 'departments');
    	    $department = reset($departments);
    	    $link = get_term_link($department);
    	
        } else {
    	
    	    $link = get_permalink();
    	
    	}
    	
    	return $link;
        
    }
	