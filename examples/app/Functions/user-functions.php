<?php
    
    function get_user_attended_events_args($user_id = null) {
		    
	    $user_id = $user_id ? $user_id : get_current_user_id(); 
	    
	    return array(
            'post_type' => 'event',
            'order' => 'ASC',
            'orderby' => 'meta_value',
            'meta_key' => 'start_date',
            'meta_query' => array(
                array(
                    'key' => 'attended_by_user',
                    'value' => 's:' . strlen($user_id) . ':"' . $user_id . '"',
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '<=',
                    'type' => 'DATE'
                )
            )
        );
	    
    }
    
    function get_user_attended_events($user_id = null) {
        
        return get_posts(get_user_attended_events_args($user_id));
        
    }
    
    function get_user_attended_events_without_feedback($user_id = null) {
	    
	    $user_id = $user_id ? $user_id : get_current_user_id();
	    
		$args = array_merge_recursive(get_user_attended_events_args($user_id), array(
			'meta_query' => array(
				array(
					'key' => 'user_' . $user_id . '_feedback_entry_id',
					'compare' => 'NOT EXISTS',
				),
			)
		));
	   
	   return get_posts($args);
	    
    }
    
    function get_user_unactioned_events_args($user_id = null) {
        
        $user_id = $user_id ? $user_id : get_current_user_id();
        
        return array(
            'post_type' => 'event',
            'meta_query' => array(
                array(
                    'key' => 'users_invited',
                    'value' => 's:' . strlen($user_id) . ':"' . $user_id . '"',
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'actioned_by_user',
                    'value' => 's:' . strlen($user_id) . ':"' . $user_id . '"',
                    'compare' => 'NOT LIKE',
                ),
                array(
                    'key' => 'actioned_by_user',
                    'value' => 's:' . strlen($user_id) . ':"' . $user_id . '"',
                    'compare' => 'NOT LIKE',
                ),
                array(
                    'key' => 'start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE'
                )
            ),
            'orderby' => 'meta_value',
            'meta_key' => 'start_date',
            'order' => 'ASC',
        );
        
    }
    
    function get_user_unactioned_events($user_id = null) {
        
        return get_posts(get_user_unactioned_events_args($user_id ? $user_id : get_current_user_id()));
        
    }
    
    function get_user_unresponded_events_args($user_id = null) {
        
        $user_id = $user_id ? $user_id : get_current_user_id();
        
        return array(
            'post_type' => 'event',
            'meta_query' => array(
                array(
                    'key' => 'users_invited',
                    'value' => 's:' . strlen($user_id) . ':"' . $user_id . '"',
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'declined_by_user',
                    'value' => 's:' . strlen($user_id) . ':"' . $user_id . '"',
                    'compare' => 'NOT LIKE',
                ),
                array(
                    'key' => 'attended_by_user',
                    'value' => 's:' . strlen($user_id) . ':"' . $user_id . '"',
                    'compare' => 'NOT LIKE',
                ),
                array(
                    'key' => 'start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE'
                )
            ),
            'orderby' => 'meta_value',
            'meta_key' => 'start_date',
            'order' => 'ASC',
        );
        
    }
    
    function get_user_unresponded_events($user_id = null) {
        
        return get_posts(get_user_unresponded_events_args($user_id ? $user_id : get_current_user_id()));
        
    }
    
    function get_user_avatar($user_id = null) {
	        
        $user_id = $user_id ? $user_id : get_current_user_id();
        
        return get_field('user_avatar', 'user_' . $user_id) ? get_field('user_avatar', 'user_' . $user_id) : THEME_DIR . '/images/avatar.png';
        
    }
    
    function get_blog_author_posts_url($blog_id, $author_id, $author_nicename = '') {
		
		$current_blog = get_current_blog_id();
	    
	    switch_to_blog($blog_id);
	    
	    $url = get_author_posts_url($author_id, $author_nicename);
	    
	    switch_to_blog($current_blog);
	    
	    return $url;
	    
	}
	
	function get_profile_url($user_id) {
		
		$current_blog = get_current_blog_id();
		
		switch_to_blog(1);
		
		$user = get_user_by('id', $user_id);
		
		$url = get_permalink(get_page_by_title('Profile')) . $user->user_login;
		
		switch_to_blog($current_blog);
		
		return $url;
		
	}
    