<?php
	
	/*----------------------------------------------*\
		#CONVERT INTEGER TO CURRENCY
	\*----------------------------------------------*/
	
	if ( ! function_exists('get_currency_symbol') ) {
	
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
	    
	}
    
    if ( ! function_exists('to_currency') ) {
    
	    function to_currency( $price, $currency = DEFAULT_CURRENCY ) {
	        
	        return sprintf( '%1$s%2$s', get_currency_symbol($currency), number_format( $price, 0 ) );
	        
	    }
	    
	}
		
	/*----------------------------------------------*\
		#OUTPUT SVG ICON CODE
	\*----------------------------------------------*/
	
	if ( ! function_exists('icon') ) {
	
		function icon($name, $css = '', $echo = true) {
			
			$icon = "<svg " . ( $css ? "class='$css'" : '' ) . "><use xlink:href='" . THEME_URI . "/images/icons.svg#icon-$name'></use></svg>";
	    
			if( $echo ) {
				
				echo $icon;
				
			} else {
				
	        	return $icon;
	        	
	        }
	    
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#NICE VAR DUMP
    \*----------------------------------------------*/
    
    if ( ! function_exists('nice') ) {
     
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
	    
	}
    
    /*----------------------------------------------*\
    	#NICE JSON VAR DUMP
    \*----------------------------------------------*/
    
    if ( ! function_exists('wp_nice_json') ) {
     
	    function wp_nice_json($json, $echo = true) {
		    
		    if ( ! WPKIT_DEBUG ) {
			    
			    status_header(200);
			    
			    wp_send_json_success( $json );
			    
			    exit();
			    
		    }
		    
		    ob_start();
		    
	        echo '<pre class="var-dump">';
	        
	        echo json_encode($json, JSON_PRETTY_PRINT);
	        
	        echo '</pre>';
	        
	        $json = ob_get_contents();
	        
	        ob_end_clean();
	        
	        if( $echo ) {
		        
		        wp_die( $json, 'WPKit Nice JSON', [ 'response' => 200 ] );
		        
	        } else {
		        
		        return $json;
		        
	        }
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#GET ELEMENT FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('get_element') ) {
    
	    function get_element($path = '', $template, $vars = array(), $echo = false) {
		    
		    $file = $path . DS . $template;
			
			$html = '';
			
			if( file_exists( COMPONENTS_DIR . DS . $file . '.twig' ) || file_exists( VIEWS_DIR . DS . $file . '.twig' ) ) {
				
				$html = twigify($file . '.twig', $vars);
	    		
	        } else if( file_exists( COMPONENTS_DIR . DS . $file . '.php') ) {
	    		
	    		ob_start();
	    		
	    		extract($vars);
	    			
				include( COMPONENTS_DIR . DS . $file . '.php' );
			
				$html = ob_get_contents();
				
				ob_end_clean();
				
			} else if( file_exists( VIEWS_DIR . DS . $file . '.php') ) {
	    		
	    		ob_start();
	    		
	    		extract($vars);
	    			
				include( VIEWS_DIR . DS . $file . '.php' );
			
				$html = ob_get_contents();
				
				ob_end_clean();
				
			}
	        
	        if($echo)
	            echo $html;
	            
	        else
	            return $html;
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#GET COMPONENT FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('get_component') ) {
    
	    function get_component($path = '', $template, $vars = array(), $echo = false) {
		    
		    $html = get_element($path, $template, $vars, $echo);
		    
		    if($echo)
	            echo $html;
	            
	        else
	            return $html;
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#THE COMPONENT FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('the_component') ) {
    
	    function the_component($path = '', $template, $vars = array()) {
		    
		    get_component($path, $template, $vars, true);
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#COMPONENT FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('component') ) {
    
	    function component($path = '', $template, $vars = array(), $echo = true) {
		    
		    $html = get_component($path, $template, $vars, $echo);
		    
		    if($echo)
	            echo $html;
	            
	        else
	            return $html;
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#GET VIEW FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('get_view') ) {
    
	    function get_view($path = '', $template, $vars = array(), $echo = false) {
		    
		    $html = get_element( $path, $template, $vars, $echo );
		    
		    if($echo)
	            echo $html;
	            
	        else
	            return $html;
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#THE VIEW FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('the_view') ) {
    
	    function the_view($path = '', $template, $vars = array()) {
		    
		    get_view($path, $template, $vars, true);
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#VIEW FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('view') ) {
    
	    function view($path = '', $template, $vars = array(), $echo = true) {
		    
		    $html = get_view($path, $template, $vars, $echo);
		    
		    if($echo)
	            echo $html;
	            
	        else
	            return $html;
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#GET ASSET FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('get_asset') ) {
    
	    function get_asset($file, $server_path = false) {
	        
	        foreach( array_map( 'trim', explode(',', ASSET_DIRS) ) as $dir ) {
	                    
	            if( file_exists( THEME_DIR . DS . $dir . DS . $file ) ) {
		            
		            $path = $server_path ? THEME_DIR : THEME_URI;
	                
	                return $path . DS . $dir . DS . $file;
	                
	            }
	            
	        }
	        
	        return false;
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#THE ASSET FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('the_asset') ) {
    
	    function the_asset($file) {
	        
	        if( $asset = get_asset( $file ) ) {
		        
		        echo $asset;
		        
	        }
	        
	    }

	}
	
    /*----------------------------------------------*\
    	#IS WP_LOGIN
    \*----------------------------------------------*/
    
    if ( ! function_exists('is_wp_login') ) {
    
	    function is_wp_login() {
	        
	        return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php', 'wp-activate.php', 'wp-signup.php' ) );
	        
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#TWIG HELPER
    \*----------------------------------------------*/
    
    if ( ! function_exists('twig_this') ) {
    
	    function twig_this($template, $data) {
		    
		    _deprecated_function( __FUNCTION__, '1.3', 'twigify' );
		    
		    return twigify($template, $data);
		    
	    }
	    
	}
	
	if ( ! function_exists('twigify') ) {
    
	    function twigify($template, $data) {
		    
		    if( class_exists('Timber') ) {
		    
		    	return Timber::compile($template, $data);
		    	
		    }
		    
		    return wpkit('Twig_Environment')->render($template, $data);
		    
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#IN, ADD & REMOVE META SERIALIZED
    \*----------------------------------------------*/
    
    if ( ! function_exists('in_metadata_serialized') ) {
    
	    function in_metadata_serialized($value, $type = 'post', $object_id, $meta_key) {
	        
	        $array = get_metadata($type, $object_id, $meta_key, true);
	                
	        return in_array($value, $array ? $array : array() );
	        
	    }
	    
	}
    
    if ( ! function_exists('update_metadata_serialized') ) {
    
	    function update_metadata_serialized($type = 'post', $object_id, $meta_key, $value, $multiple = false) {
	        
			if( $multiple || ! in_metadata_serialized($value, $type, $object_id, $meta_key) ) { 
	    		
	    		$array = get_metadata($type, $object_id, $meta_key, true);
	    		
	    		$array = $array ? $array : array();
	    		
	    		$array[] = $value;
	    		
	    		update_metadata($type, $object_id, $meta_key, $array);	
			
			}
	        
	    }
	    
	}
    
    if ( ! function_exists('remove_metadata_serialized') ) {
    
	    function remove_metadata_serialized($type = 'post', $object_id, $meta_key, $value) {
	        
	        $array = get_metadata($type, $object_id, $meta_key, true);
	    			
			$array = $array ? $array : array();
			
			$array_index = array_search($value, $array);
			
			if( $array_index > -1 ) {
				
				unset($array[$array_index]);
				
			}
			
			update_metadata($type, $object_id, $meta_key, $array);	
	        
	    }
	    
	}
    
     /*----------------------------------------------*\
    	#GET RAW ARCHIVE TITLE
    \*----------------------------------------------*/
    
    if ( ! function_exists('get_the_raw_archive_title') ) {
    
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
	    
	}
    
    /*----------------------------------------------*\
    	#INFLECTOR FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('inflector') ) {
    
	    function inflector() {
		    
		    return ICanBoogie\Inflector::get(INFLECTOR_DEFAULT_LOCALE);
		    
	    }
	    
	}
    
    
    /*----------------------------------------------*\
    	#WPKIT FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('wpkit') ) {
    
	    function wpkit($binding = null) {
		    
		   $instance = WPKit\Core\Application::getInstance(realpath(APP_ROOT));
		    
		    if ( ! $binding ) {
			    
	            return $instance;
	            
	        }
	        
	        return $instance->make($binding);
		    
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#INVOKE FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('invoke') ) {
    
	    function invoke( $callback, $action = 'wp', $condition = null, $priority = null ) {
		    
		    $priority = is_null( $priority ) ? ( is_numeric( $condition ) ? $condition : 10 ) : $priority;
		    
		    if( is_null( $condition ) || $condition === $priority ) {
			    
			    return wpkit('invoker')->invoke( $callback, $action, $priority );
			    
		    } else {
			    
			    return wpkit('invoker')->invokeByCondition( $callback, $action, $condition, $priority );
			    
		    }
		    
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#ROUTES
    \*----------------------------------------------*/
    
    if ( ! function_exists('route') ) {
    
	    function route( $uri, $callback, $method = 'get' ) {
		    
		    if( php_sapi_name() === 'cli' ) {
			    
			    return false;
			    
			}
		    
		    if( is_string( $callback ) ) {
			    
			    $callback = str_replace( '::' ,'@', $callback );
			    
		    }
		    
		    $methods = $method == '*' ? ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'] : ( is_array( $method ) ? $method : [$method] );
		    
		    $callback = is_string( $callback ) ? wpkit()->prependNamespace( $callback ) : $callback;
		    
		    return Illuminate\Support\Facades\Route::match( $methods, $uri, $callback );
		    
	    }
	    
	}
	
	if( ! function_exists('get_current_url') ) {
		
		function get_current_url() {
			
			return get_home_url( 1, wpkit('http')->getRequestUri() );
			
		}
		
	}
	
	if( ! function_exists('get_current_url_path') ) {
		
		function get_current_url_path() {
			
			return rtrim( explode('?', get_current_url())[0], '/');
			
		}
		
	}
	
	if ( ! function_exists('is_route') ) {
    
	    function is_route( $path ) {
		    
		    if( strpos( $path, '*' ) !== false ) {
			    
			    $is_route = strpos( get_current_url_path(), home_url( str_replace( '*', '', $path ) ) ) !== false;
			    
		    } else {
		    
		    	$is_route = home_url( $path ) == get_current_url_path();
		    	
		    }

			return $is_route;
		    
	    }
	    
	}
    
    /*----------------------------------------------*\
    	#MULTISITE
    \*----------------------------------------------*/
    
    if ( ! function_exists('get_wpmu_query') ) {
    	    
	    function get_wpmu_query($args = array()) {
			
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
			
			$blogs = get_sites( $blogArgs );
			
			foreach($blogs as $i => $blog) {
				
				$status = get_blog_status($blog->blog_id, 'public');
				
				if( ! $status && ( ! is_user_logged_in() || ( ! is_user_member_of_blog(get_current_user_id(), $blog->blog_id) && !is_super_admin() ) ) )
					unset($blogs[$i]);
				
			}
			
			$args = array_merge(array(
				'posts_per_page'   => get_blog_option( 1, 'posts_per_page' ),
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
				'paged' => get_query_var('page') ? get_query_var('page') : 1, 
			), $args);
			
			extract($args);
			
			$args['posts_per_page'] = -1;
			$args['paged'] = 1;
			
			$orderbyVal = $orderby === 'meta_value' ? $meta_key : $orderby;
			
			$posts = array();
			
			$current_blog = get_current_blog_id();
			
			foreach($blogs as $blog) {
				
				if( $author && ! is_user_member_of_blog( $author, $blog->blog_id ) ) {
					
					continue;
					
				}
				
				switch_to_blog($blog->blog_id);
				
				$blog_posts = get_posts($args);
				
				foreach($blog_posts as $blog_post) {
					
					$blog_post->blog_id = $blog->blog_id;
					$blog_post->$orderbyVal = $blog_post->$orderbyVal;
					
					$posts[] = $blog_post;
					
					
				}
				
			}
			
			switch_to_blog($current_blog);
			
			uasort($posts, function($a, $b) use($orderby, $orderbyVal)  {
				
				if( $orderby === 'date' ) {
					
					$ordering_a = strtotime($a->$orderbyVal);
					$ordering_b = strtotime($b->$orderbyVal);
					
				}
						
				else {
					
					$ordering_a = $a->$orderbyVal;
					$ordering_b = $b->$orderbyVal;
					
				}
				
				return $ordering_a > $ordering_b ? -1 : ( $ordering_a == $ordering_b ? 0 : 1 );
				
			});
			
			if( $order === 'DESC' ) {
				
				$posts = array_reverse($posts);
				
			}
			
			$query = new WP_Query( $args );
			
			$query->found_posts = count( $posts );
			
			$query->is_paged = $paged > 1;
			
			$query->posts = array_values( array_slice( $posts, ( $paged -1 ) * $posts_per_page, $posts_per_page ) );
			
			$query->post_count = $posts_per_page;
			
			$query->max_num_pages = ceil( $query->found_posts / $query->post_count );
			
			return $query;
			
		}
	}
	
	if( ! function_exists( 'get_wpmu_site_slug' ) ) {
		
		function get_wpmu_site_slug() {
			
			return str_replace( get_site_url(1), '', get_site_url() );
			
		}
		
	}
	
	/*----------------------------------------------*\
    	#FORCE REST
    \*----------------------------------------------*/
    
    if ( ! function_exists('force_rest') ) {
	
		function force_rest($controller = '\WPKit\Http\Controllers\RestController') {
				
			$restController = wpkit()->make($controller);
			
			route( '{controller}/{action}/{id}', $controller . '@action', '*' );
			route( '{controller}/{action}', $controller . '@action', '*' );
			route( '{controller}', $controller . '@action', '*' );
			
		}
		
	}
	
	/*----------------------------------------------*\
    	#AUTH
    \*----------------------------------------------*/
    
    if ( ! function_exists('auth') ) {
		
		function auth($auth, $settings = array(), $callback = null) {
			
			if( ! is_callable($callback) ) {
				
				$callback = function(){};
				
			}
			
			$auth = wpkit(  'auth.' . $auth  );
			
			if( method_exists( $auth, 'mergeSettings' ) && $settings ) {
				
				$auth->mergeSettings( $settings );
				
			}
			
			return $auth->handle( wpkit( 'http' ), $callback );
			
		}
		
	}
	
	/*----------------------------------------------*\
    	#SESSION
    \*----------------------------------------------*/
	
	if ( ! function_exists('session') ) {
		
	    /**
	     * Gets the session or a key from the session.
	     *
	     * @param  string $key
	     * @param  mixed  $default
	     * @return \Illuminate\Session\Store|mixed
	     */
	    function session($key = null, $default = null)
	    {
		     if (is_null($key)) {
	            return wpkit('session');
	        }
	        if (is_array($key)) {
	            return wpkit('session')->put($key);
	        }
	        return wpkit('session')->get($key, $default);
	    }
	}
	
	if ( ! function_exists('notifier') ) {
	    /**
	     * Gets the session flashbag or a key from the session flashbag.
	     *
	     * @param  string $key
	     * @param  mixed  $default
	     * @return \Illuminate\Session\Store|mixed
	     */
	    function notifier($notifier = 'frontend')
	    {
	        switch($notifier) {
		        case 'admin' :
		        	$notifier = 'WPKit\Notifications\Notifiers\AdminNotifier';
		        break;
		        default :
		        	$notifier = 'WPKit\Notifications\Notifiers\FrontEndNotifier';
		        break;
	        }
	        return wpkit($notifier);
	    }
	}
	
	/*----------------------------------------------*\
    	#NGINX FIXES
    \*----------------------------------------------*/
	
	// apache_request_headers replicement for nginx
	if ( ! function_exists('apache_request_headers') ) {
		 
        function apache_request_headers() { 
	        
            foreach($_SERVER as $key  =>$value) { 
	            
                if (substr($key,0,5)=="HTTP_") { 
	                
                    $key = str_replace(" ","-", ucwords( strtolower( str_replace( "_", " ", substr($key,5) ) ) ) ); 
                    
                    $out[$key]=$value; 
                    
                } else {
	                
                    $out[$key]=$value; 
                    
				}
            } 
            
            return $out; 
            
        } 
	        
	}
	
	/*----------------------------------------------*\
    	#LARAVEL FUNCTIONS
    \*----------------------------------------------*/
	
	if (! function_exists('storage_path')) {
    /**
	     * Get the path to the storage folder.
	     *
	     * @param  string  $path
	     * @return string
	     */
	    function storage_path($path = '')
	    {
	        return wpkit()->storagePath().($path ? DIRECTORY_SEPARATOR.$path : $path);
	    }
	}
	
	if (! function_exists('config')) {
	    /**
	     * Get / set the specified configuration value.
	     *
	     * If an array is passed as the key, we will assume you want to set an array of values.
	     *
	     * @param  array|string  $key
	     * @param  mixed  $default
	     * @return mixed
	     */
	    function config($key = null, $default = null)
	    {
	        if (is_null($key)) {
	            return wpkit('config');
	        }
	        if (is_array($key)) {
	            return wpkit('config')->set($key);
	        }
	        return wpkit('config')->get($key, $default);
	    }
	}
	
	if (! function_exists('base_path')) {
	    /**
	     * Get the path to the base of the install.
	     *
	     * @param  string  $path
	     * @return string
	     */
	    function base_path($path = '')
	    {
	        return wpkit()->basePath().($path ? DIRECTORY_SEPARATOR.$path : $path);
	    }
	}
	
	if (! function_exists('app_path')) {
	    /**
	     * Get the path to the application folder.
	     *
	     * @param  string  $path
	     * @return string
	     */
	    function app_path($path = '')
	    {
	        return wpkit()->path().($path ? DIRECTORY_SEPARATOR.$path : $path);
	    }
	}
	
	if ( ! function_exists('fastcgi_finish_request') ) {
		
		function fastcgi_finish_request() {
		 
        	die();
        	
        }
            
	}

?>
