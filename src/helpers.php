<?php
	
	/*----------------------------------------------*\
		#CONVERT INTEGER TO CURRENCY
	\*----------------------------------------------*/
	
	function get_currency_symbol( $currency = DEFAULT_CURRENCY ) {
    
        $symbols = array(
            'AED' => 'د.إ',
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'BDT' => '&#2547;&nbsp;',
            'BGN' => '&#1083;&#1074;.',
            'BRL' => '&#82;&#36;',
            'CAD' => '&#36;',
            'CHF' => '&#67;&#72;&#70;',
            'CLP' => '&#36;',
            'CNY' => '&yen;',
            'COP' => '&#36;',
            'CZK' => '&#75;&#269;',
            'DKK' => 'DKK',
            'DOP' => 'RD&#36;',
            'EGP' => 'EGP',
            'EUR' => '&euro;',
            'GBP' => '&pound;',
            'HKD' => '&#36;',
            'HRK' => 'Kn',
            'HUF' => '&#70;&#116;',
            'IDR' => 'Rp',
            'ILS' => '&#8362;',
            'INR' => '&#8377;',
            'ISK' => 'Kr.',
            'JPY' => '&yen;',
            'KES' => 'KSh',
            'LAK' => '&#8365;',
            'KRW' => '&#8361;',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'NGN' => '&#8358;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PYG' => '&#8370;',
            'RMB' => '&yen;',
            'RON' => 'lei',
            'RUB' => '&#1088;&#1091;&#1073;.',
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'THB' => '&#3647;',
            'TRY' => '&#8378;',
            'TWD' => '&#78;&#84;&#36;',
            'UAH' => '&#8372;',
            'USD' => '&#36;',
            'VND' => '&#8363;',
            'ZAR' => '&#82;',
        );
    
        return isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

    }
    
    function to_currency( $price, $currency = DEFAULT_CURRENCY ) {
        
        return sprintf( '%1$s%2$s', get_currency_symbol($currency), number_format( $price, 0 ) );
        
    }
	
	/*----------------------------------------------*\
		#OUTPUT SVG ICON CODE
	\*----------------------------------------------*/
	
	function icon($name, $css = '', $echo = true) {
		
		$icon = "<svg " . ( $css ? "class='$css'" : '' ) . "><use xlink:href='" . THEME_URI . "/images/icons.svg#icon-$name'></use></svg>";
    
		if( $echo ) {
			
			echo $icon;
			
		} else {
			
        	return $icon;
        	
        }
    
    }
    
    /*----------------------------------------------*\
    	#NICE VAR DUMP
    \*----------------------------------------------*/
     
    function nice($content, $echo = true) {
	    
	    ob_start();
	    
        echo '<pre class="var-dump">';
        
        print_r($content);
        
        echo '</pre>';
        
        $html = ob_get_contents();
        
        ob_end_clean();
        
        if( $echo ) {
	        
	        echo $html;
	        
        } else {
	        
	        return $html;
	        
        }
        
    }
    
    
    /*----------------------------------------------*\
    	#GET COMPONENT FUNCTION
    \*----------------------------------------------*/
    
    function get_component($path = '', $template, $vars = array(), $echo = true) {
	    
	    $file = COMPONENTS_DIR . DS . $path . DS . $template;
		
		$html = '';
		
		if( file_exists($file . '.twig') ) {
    		
    		$html = Timber::compile($path . DS . $template . '.twig', $vars);
    		
        } else if( file_exists($file . '.php') ) {
    		
    		ob_start();
    		
    		extract($vars);
    			
			include( $file . '.php' );
		
			$html = ob_get_contents();
			
			ob_end_clean();
			
		}
        
        if($echo)
            echo $html;
            
        else
            return $html;
        
    }
    
    /*----------------------------------------------*\
    	#GET ASSET FUNCTION
    \*----------------------------------------------*/
    
    function get_asset($file) {
        
        foreach( array_map( 'trim', explode(',', ASSET_DIRS) ) as $dir ) {
                    
            if( file_exists( THEME_DIR . DS . $dir . DS . $file ) ) {
                
                return THEME_URI . DS . $dir . DS . $file;
                
            }
            
        }
        
        return false;
        
    }
    
    /*----------------------------------------------*\
    	#THE ASSET FUNCTION
    \*----------------------------------------------*/
    
    function the_asset($file) {
        
        if( $asset = get_asset( $file ) ) {
	        
	        echo $asset;
	        
        }
        
    }
    
    /*----------------------------------------------*\
    	#IS WP_LOGIN
    \*----------------------------------------------*/
    
    function is_wp_login() {
        
        return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
        
    }
    
    /*----------------------------------------------*\
    	#TWIG HELPER
    \*----------------------------------------------*/
    
    function twig_this($template, $data) {
	    
	    $loader = new TimberLoader( Timber::get_calling_script_dir() );
	    
	    return $loader->get_twig()->createTemplate($template)->render($data);
	    
    }
    
    /*----------------------------------------------*\
    	#IN, ADD & REMOVE META SERIALIZED
    \*----------------------------------------------*/
    
    function in_metadata_serialized($value, $type = 'post', $object_id, $meta_key) {
        
        $array = get_metadata($type, $object_id, $meta_key, true);
                
        return in_array($value, $array ? $array : array() );
        
    }
    
    function update_metadata_serialized($type = 'post', $object_id, $meta_key, $value, $multiple = false) {
        
		if( $multiple || ! in_metadata_serialized($value, $type, $object_id, $meta_key) ) { 
    		
    		$array = get_metadata($type, $object_id, $meta_key, true);
    		
    		$array = $array ? $array : array();
    		
    		$array[] = $value;
    		
    		update_metadata($type, $object_id, $meta_key, $array);	
		
		}
        
    }
    
    function remove_metadata_serialized($type = 'post', $object_id, $meta_key, $value) {
        
        $array = get_metadata($type, $object_id, $meta_key, true);
    			
		$array = $array ? $array : array();
		
		$array_index = array_search($value, $array);
		
		if( $array_index > -1 ) {
			
			unset($array[$array_index]);
			
		}
		
		update_metadata($type, $object_id, $meta_key, $array);	
        
    }
    
     /*----------------------------------------------*\
    	#IN, ADD & REMOVE META SERIALIZED
    \*----------------------------------------------*/
    
    function get_the_raw_archive_title() {
        global $post;
        if ( is_category() ) {
            $title = single_cat_title( '', false );
        } elseif ( is_tag() ) {
            $title = single_tag_title( '', false );
        } elseif ( is_author() ) {
            $title = '<span class="vcard">' . get_the_author() . '</span>';
        } elseif ( is_year() ) {
            $title = get_the_date( _x( 'Y', 'yearly archives date format' ) );
        } elseif ( is_month() ) {
            $title = get_the_date( _x( 'F Y', 'monthly archives date format' ) );
        } elseif ( is_day() ) {
            $title = get_the_date( _x( 'F j, Y', 'daily archives date format' ) );
        } elseif ( is_tax( 'post_format' ) ) {
            if ( is_tax( 'post_format', 'post-format-aside' ) ) {
                $title = _x( 'Asides', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
                $title = _x( 'Galleries', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
                $title = _x( 'Images', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
                $title = _x( 'Videos', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
                $title = _x( 'Quotes', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
                $title = _x( 'Links', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
                $title = _x( 'Statuses', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
                $title = _x( 'Audio', 'post format archive title' );
            } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
                $title = _x( 'Chats', 'post format archive title' );
            }
        } elseif ( is_post_type_archive() ) {
            $title = post_type_archive_title( '', false );
        } elseif ( is_tax() ) {
            $tax = get_taxonomy( get_queried_object()->taxonomy );
            /* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
            $title = single_term_title( '', false );
        } elseif( get_queried_object() instanceof WP_Post ) {
            $title = __( get_queried_object()->post_title );
        } elseif ( is_search() ) {
            $title = sprintf( __( 'Search Results: &ldquo;%s&rdquo;', 'woocommerce' ), get_search_query() );
        }  elseif( $post ) {
            $title = get_post_type_object($post->post_type)->labels->name;
        } else {
            $title = __( 'Archives' );
        }
     
        /**
         * Filter the archive title.
         *
         * @since 4.1.0
         *
         * @param string $title Archive title to be displayed.
         */
        return apply_filters( 'get_the_archive_title', $title );
    }
    
    /*----------------------------------------------*\
    	#INFLECTOR FUNCTION
    \*----------------------------------------------*/
    
    function inflector() {
	    
	    return ICanBoogie\Inflector::get(INFLECTOR_DEFAULT_LOCALE);
	    
    }
    
    
    /*----------------------------------------------*\
    	#WPKIT FUNCTION
    \*----------------------------------------------*/
    
    function wpkit() {
	    
	    return WPKit\Application::instance();
	    
    }
    
    /*----------------------------------------------*\
    	#INVOKE FUNCTION
    \*----------------------------------------------*/
    
    function invoke( $callback, $action = 'wp', $condition = null, $priority = null ) {
	    
	    $priority = is_null( $priority ) ? ( is_numeric( $condition ) ? $condition : 20 ) : 20;
	    
	    if( is_null( $condition ) ) {
		    
		    return wpkit()->make('invoker')->invoke( $callback, $action, $priority );
		    
	    } else {
		    
		    return wpkit()->make('invoker')->invokeByCondition( $callback, $action, $condition, $priority );
		    
	    }
	    
    }
    
    /*----------------------------------------------*\
    	#ROUTE FUNCTION
    \*----------------------------------------------*/
    
    function route( $route, $callback, $method = 'get' ) {
	    
	    return wpkit()->make('router')->map( $route, $callback, $method );
	    
    }
    
    /*----------------------------------------------*\
    	#MULTISITE
    \*----------------------------------------------*/
    	    
    function get_wpmu_posts($args = array()) {
		
		global $wpdb;
		
		$blogArgs = array(
		    'network_id' => $wpdb->siteid,
		    'public'     => is_user_logged_in() ? null : 1,
		    'archived'   => null,
		    'mature'     => null,
		    'spam'       => null,
		    'deleted'    => null,
		    'limit'      => 999,
		    'offset'     => 1,
		);
		
		$blogs = wp_get_sites( $blogArgs );
		
		foreach($blogs as $i => $blog) {
			
			$status = get_blog_status($blog['blog_id'], 'public');
			
			if( ! $status && ( ! is_user_logged_in() || ( ! is_user_member_of_blog(get_current_user_id(), $blog['blog_id']) && !is_super_admin() ) ) )
				unset($blogs[$i]);
			
		}
		
		$args = array_merge(array(
			'posts_per_page'   => 5,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'post',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'paged' => get_query_var('paged') ? get_query_var('paged') : 1, 
		), $args);
		
		extract($args);
		
		$args['posts_per_page'] = -1;
		$args['paged'] = 1;
		
		$orderbyVal = $orderby === 'meta_value' ? $meta_key : $orderby;
		
		$posts = array();
		
		foreach($blogs as $blog) {
			
			switch_to_blog($blog['blog_id']);
			
			$blog_posts = get_posts($args);
			
			foreach($blog_posts as $blog_post) {
				
				$blog_post->blog_id = $blog['blog_id'];
				
				if($orderby === 'date') 
					$ordering = strtotime($blog_post->$orderbyVal);
					
				else
					$ordering = $blog_post->$orderbyVal;
				
				while(isset($posts[$ordering])) {
					
					$ordering = $ordering+1;
					
				}
				
				$posts[$ordering] = $blog_post;
				
			}
			
		}
		
		switch_to_blog($current_blog);
		
		krsort($posts);
		
		if($posts_per_page == -1)
			return array_slice($posts, 0, count($posts));
			
		else
			return array_slice($posts, ($paged-1)*$posts_per_page, $posts_per_page);
		
	}

?>
