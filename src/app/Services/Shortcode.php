<?php
    
    namespace WPKit\Services;

	class Shortcode {
    	
    	/**
	     * If the shortcode should be used with JsComposer integration
	     *
	     * @var boolean
	     */
    	var $compose = true;    	
		
		/**
	     * The name of the shortcode
	     *
	     * @var string
	     */
    	var $name = '';
    	
    	/**
	     * The tag of the shortcode [shortcode]
	     *
	     * @var string
	     */
        var $base = '';
        
        /**
	     * The descirption of the shortcode
	     *
	     * @var string
	     */
        var $description = '';
        
        /**
	     * The classname of the shortcode
	     *
	     * @var string
	     */
        var $class = '';
        
        /**
	     * For JSComposer - should we show settings on create of component?
	     *
	     * @var boolean
	     */
        var $show_settings_on_create = false;
        
        /**
	     * For JSComposer - is the component a container?
	     *
	     * @var boolean
	     */
        var $is_container = false;
        
        /**
	     * For JSComposer - parent components
	     *
	     * @var array
	     */
        var $as_parent = array();
        
        /**
	     * For JSComposer - child components
	     *
	     * @var array
	     */
        var $as_child = array();
        
        /**
	     * For JSComposer - weight / importance of component
	     *
	     * @var int
	     */
        var $weight = 1;
        
        /**
	     * For JSComposer - category of component
	     *
	     * @var string
	     */
        var $category = '';
        
        /**
	     * For JSComposer - group of component
	     *
	     * @var string
	     */
        var $group = '';
        
        /**
	     * For JSComposer - admin js file to enqueue
	     *
	     * @var string
	     */
        var $admin_enqueue_js = '';
        
        /**
	     * For JSComposer - admin css file to enqueue
	     *
	     * @var string
	     */
        var $admin_enqueue_css = '';
        
        /**
	     * For JSComposer - frontend js file to enqueue
	     *
	     * @var string
	     */
        var $front_enqueue_js = '';
        
        /**
	     * For JSComposer - frontend css file to enqueue
	     *
	     * @var string
	     */
        var $front_enqueue_css = '';
        
        /**
	     * For JSComposer - the icon for the component
	     *
	     * @var string
	     */
        var $icon = '';
        
        /**
	     * For JSComposer - custom markup
	     *
	     * @var string
	     */
        var $custom_markup = '';
        
        /**
	     * For JSComposer - js-view
	     *
	     * @var string
	     */
        var $js_view = '';
        
        /**
	     * For JSComposer - html template
	     *
	     * @var string
	     */
        var $html_template = '';
        
        /**
	     * For JSComposer - if component is deprecated
	     *
	     * @var boolean
	     */
        var $deprecated = false;
        
        /**
	     * For JSComposer - if component is a content element
	     *
	     * @var boolean
	     */
        var $content_element = true;
        
        /**
	     * The params of the shortcodes used in shortcode_atts and JsComposer
	     *
	     * @var array
	     */
        var $params = array();
        
        /**
	     * The constructor
	     *
	     * @return void
		 */
        public function __construct() {
            
            if( $this->base ) {
                
                add_shortcode( $this->base, array( $this, 'shortcode') ); 
                
            }
    		
		}
		
		/**
	     * Convert shortcode to array
	     *
	     * @return array
		 */
		public function to_array() {
    		
    		return array_filter( array(
                'name' => $this->name,
                'base' => $this->base,
                'description' => $this->description,
                'class' => $this->class,
                'show_settings_on_create' => $this->show_settings_on_create,
                'is_container' => $this->is_container,
                'as_parent' => $this->as_parent,
                'as_child' => $this->as_child,
                'weight' => $this->weight,
                'category' => $this->category,
                'group' => $this->group,
                'admin_enqueue_js' => $this->admin_enqueue_js,
                'admin_enqueue_css' => $this->admin_enqueue_css,
                'front_enqueue_js' => $this->front_enqueue_js,
                'front_enqueue_css' => $this->front_enqueue_css,
                'icon' => $this->icon,
                'custom_markup' => $this->custom_markup,
                'js_view' => $this->js_view,
                'html_template' => $this->html_template,
                'deprecated' => $this->deprecated,
                'content_element' => $this->content_element,
                'params' => $this->get_params()
            ) );
    		
		}
		
		/**
	     * Get params values
	     *
	     * @return array
		 */
		public function get_params() {
    		
    		return array_values($this->params);
    		
		}
		
		/**
	     * Render the shortcode
	     *
	     * @param array $atts
	     * @param string $content
	     * @return string
		 */
		public function shortcode( $atts = array(), $content = '' ) {
    		
    		$atts = $this->logic(shortcode_atts($this->get_default_atts(), $atts, $this->base));
    		
    		if( $content ) {
        		
        		$atts['content'] = do_shortcode($content);
        		
    		}
    		
    		return get_component('Shortcode', $this->base, $atts);
    		
		}
		
		/**
	     * Get shortcode filename
	     *
	     * @return string
		 */
		public function filename() {
    		
    		return $this->base;
    		
		}
		
		/**
	     * Run any custom logic on $atts
	     *
	     * @param array $atts
	     * @return array
		 */
		public function logic($atts) {
    		
    		foreach($atts as $key => &$att) {
        		
        		if( $param = isset( $this->params[$key] ) ? $this->params[$key] : null ) {
        		
	        		switch($param['type']) {
	            		
	            		case 'vc_link' :
	            		
	            		    $att = vc_build_link($att);
	            		    
	                    break;
	                    
	                    case 'attach_image' :
	                        
	                        $att = wp_get_attachment_image_src($att, 'large')[0];
	                        
	                    break;
	            		
	        		}
					
				}
        		
    		}
    		
    		return $atts;
    		
		}
		
		/**
	     * Get shortcode default atts from params
	     *
	     * @return array
		 */
		private function get_default_atts() {
    		
    		return array_combine(
                array_map(function($param) { 
            		return $param['param_name'];
                }, $this->params),
        		array_map(function($param) { 
            		return ! empty( $param['default'] ) ? $param['default'] : '';
                }, $this->params)
            );
    		
		}
    	
    }
