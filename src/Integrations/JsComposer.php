<?php
    
    namespace WPKit\Integrations;
    
    use WPKit\Application as WPKit;
    use WPKit\Core\Integration;
    use WPBMap;

	class JsComposer extends Integration {
		
		var $settings = array(
			'params' => array(),
			'support' => array()
		);
    	
    	public function __construct( $settings ) {
        	
        	$this->settings = is_array($settings) ? array_merge($this->settings, $settings) : array();
        	
        	if( ! function_exists('vc_set_shortcodes_templates_dir') ) {
            	return;
        	}
        	
        	vc_set_shortcodes_templates_dir( COMPONENTS_DIR . DS . 'vc' );
        	
        	foreach( WPKit::get_shortcodes() as $shortcode ) {
    	        
    	        if( $shortcode->compose ) {
        	        
        	        vc_map( $shortcode->to_array() );
        	        
    	        }
    	        
	        }
        	
        	add_action( 'wp_enqueue_scripts', array($this, 'remove_vc_styles'), 99 );
        	add_filter( 'vc_shortcodes_css_class', array($this, 'custom_css_classes'), 10, 3 );
        	add_action( 'vc_after_init', array($this, 'add_vc_params') );
        	
        	spl_autoload_register(function($className) {
            	
                $file = vc_path_dir( 'SHORTCODES_DIR', strtolower(str_replace('_', '-', str_replace('WPBakeryShortCode_' , '', $className))) . '.php');
                
                if( file_exists($file) ) {
                    
                    require $file;
                    
                }
            	
            });
            
        }
        
        public function add_vc_params() {
	        
	        foreach( WPBMap::getAllShortCodes() as $base => $element ) {
    	        
    	        if( ! in_array( $base, array_merge( $this->settings['support'], array_keys( WPKit::get_shortcodes() ) ) ) ) {
        	        
        	        WPBMap::dropShortcode( $base );
    	            
                }
    	        
	        }
	        
	        foreach( $this->settings['params'] as $param ) {
		        
		    	foreach( $param['shortcodes'] as $shortcode ) {
    		    	
    		    	vc_add_param( $shortcode, $param );
			    	
		    	}
		        
	        }

        }
        
        public function remove_vc_styles() {
            
            if( empty( $_REQUEST['vc_editable'] ) ) {
                
                wp_deregister_style( 'js_composer_front' );
                wp_deregister_script( 'wpb_composer_front_js' );
                
            }
            
        }
        
        public function custom_css_classes( $class_string, $tag, $atts ) {
            
            if ( $tag == 'vc_row' || $tag == 'vc_row_inner' ) {
	            $class = ! empty( $this->settings['replace']['vc_row'] ) ? $this->settings['replace']['vc_row'] : 'grid';
	            $class .= ! empty( $atts['full_width'] ) ? ( ! empty( $this->settings['replace']['vc_row-fluid'] ) ? $this->settings['replace']['vc_row-fluid'] : ' grid--edge' ) : '';
                $class_string = str_replace( 'vc_row-fluid', $class, $class_string );
                $class_string = str_replace( array('vc_row', 'wpb_row', 'vc_inner'), array('', '', ''), $class_string );
            }
            if ( $tag == 'vc_column' || $tag == 'vc_column_inner' ) {
                $class_string = str_replace( 'wpb_column vc_column_container', ! empty( $this->settings['replace']['wpb_column'] ) ? $this->settings['replace']['wpb_column'] : 'grid__item', $class_string );
                $class_string = preg_replace( '/vc_col-xs-(\d{1,2})/', ! empty( $this->settings['replace']['vc_col-xs-$1'] ) ? $this->settings['replace']['vc_col-xs-$1'] : 'size-$1', $class_string );
                $class_string = preg_replace( '/vc_col-sm-(\d{1,2})/', ! empty( $this->settings['replace']['vc_col-sm-$1'] ) ? $this->settings['replace']['vc_col-sm-$1'] : 'size-$1-m', $class_string );
                $class_string = preg_replace( '/vc_col-md-(\d{1,2})/', ! empty( $this->settings['replace']['vc_col-md-$1'] ) ? $this->settings['replace']['vc_col-md-$1'] : 'size-$1-l', $class_string );
                $class_string = preg_replace( '/vc_col-lg-(\d{1,2})/', ! empty( $this->settings['replace']['vc_col-lg-$1'] ) ? $this->settings['replace']['vc_col-lg-$1'] : 'size-$1-xl', $class_string );
            }
            return $class_string;
            
        }
        
    }