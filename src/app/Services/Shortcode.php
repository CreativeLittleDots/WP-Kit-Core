<?php
    
    namespace WPKit\Services;

	class Shortcode {
    	
    	var $compose = true;    	

    	var $name = '';
        var $base = '';
        var $description = '';
        var $class = '';
        var $show_settings_on_create = false;
        var $is_container = false;
        var $as_parent = array();
        var $as_child = array();
        var $weight = 1;
        var $category = '';
        var $group = '';
        var $admin_enqueue_js = '';
        var $admin_enqueue_css = '';
        var $front_enqueue_js = '';
        var $front_enqueue_css = '';
        var $icon = '';
        var $custom_markup = '';
        var $js_view = '';
        var $html_template = '';
        var $deprecated = false;
        var $content_element = true;
        var $params = array();
        
        public function __construct() {
            
            if( $this->base ) {
                
                add_shortcode( $this->base, array( $this, 'shortcode') ); 
                
            }
    		
		}
		
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
		
		public function get_params() {
    		
    		return array_values($this->params);
    		
		}
		
		public function shortcode( $atts = array(), $content = '' ) {
    		
    		$atts = $this->logic(shortcode_atts($this->get_default_atts(), $atts, $this->base));
    		
    		if( $content ) {
        		
        		$atts['content'] = do_shortcode($content);
        		
    		}
    		
    		return get_component('Shortcode', $this->base, $atts);
    		
		}
		
		public function filename() {
    		
    		return $this->base;
    		
		}
		
		public function logic($atts) {
    		
    		foreach($atts as $key => &$att) {
        		
        		$param = $this->params[$key];
        		
        		switch($param['type']) {
            		
            		case 'vc_link' :
            		
            		    $att = vc_build_link($att);
            		    
                    break;
                    
                    case 'attach_image' :
                        
                        $att = wp_get_attachment_image_src($att, 'large')[0];
                        
                    break;
            		
        		}
        		
    		}
    		
    		return $atts;
    		
		}
		
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