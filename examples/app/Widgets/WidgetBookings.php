<?php
    
    namespace App\Widgets;
    
    use WP_Query;
    use WP_Widget;
    
    /**
     * Job Listing: Bookings
     *
     * @since domain 1.0.0
     */
    class WidgetBookings extends WP_Widget {
    
    	/**
    	 * Constructor
    	 */
    	public function __construct() {
    		$this->widget_description = __( 'Display the booking form for the linked bookable product.', 'domain' );
    		$this->widget_id          = 'widget_panel_bookings';
    		$this->widget_name        = __( 'Custom - Product: Bookings', 'domain' );
    		$this->settings           = array(
    			'title' => array(
    				'type'  => 'text',
    				'std'   => '',
    				'label' => __( 'Title:', 'domain' )
    			),
    			'icon' => array(
                    'type'    => 'text',
                    'std'     => 'ion-ios-book',
                    'label'   => '<a href="http://ionicons.com/">' . __( 'Icon Class:', 'domain' ) . '</a>'
                )
    		);
    		
    		parent::__construct($this->widget_id, $this->widget_name, $this->settings);
    		
    	}
    
    	/**
    	 * widget function.
    	 *
    	 * @see WP_Widget
    	 * @access public
    	 * @param array $args
    	 * @param array $instance
    	 * @return void
    	 */
    	function widget( $args, $instance ) {
    		
    		if ( $this->get_cached_widget( $args ) )
    			return;
    			
    		if( ! empty( $_GET['selected_package'] ) ) {
    	    	
    	    	return;
    	    	
        	}
    
    		extract( $args );
    
    		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
    		$icon = isset( $instance[ 'icon' ] ) ? $instance[ 'icon' ] : null;
    
    		if ( $icon ) {
    			$before_title = sprintf( $before_title, 'ion-' . $icon );
    		}
    
    		ob_start();
    
    		global $post, $product;		
    
    		$products = $this->get_bookable_products($post->ID);
    		
    		$job_id = $post->ID;
    		
    		if ( ( ! $products ) ) {
    			return;
    		}
    
    		echo $before_widget;
    
    		if ( $title ) echo $before_title . $title . $after_title;
            
            $booking_calendar = $this->get_booking_calendar(reset($products));
    		
            get_component( 'element', 'booking-product', compact('products', 'booking_calendar') );
    
    		echo $after_widget;
    
    		$content = ob_get_clean();
    
    		echo apply_filters( $this->widget_id, $content );
    
    		$this->cache_widget( $args, $content );
    		
    	}
     	
    }