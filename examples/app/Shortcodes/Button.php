<?php
    
    namespace App\Shortcodes;
    
    use WPKit\Core\Shortcode;
    
    class Button extends Shortcode {
        
        var $name = 'Button';
        var $base = 'button';
        var $icon = 'icon-wpb-ui-button';
        var $description = 'A nice button';
        var $category = 'Content';
        var $params = [
	        'href' => [
                'type' => 'text',
                'class' => '',
                'heading' => 'Content',
                'param_name' => 'href'
            ],
            'class' => [
                'type' => 'text',
                'class' => '',
                'heading' => 'Content',
                'param_name' => 'class'
            ],
            'content' => [
                'type' => 'textarea_html',
                'class' => '',
                'heading' => 'Content',
                'param_name' => 'content'
            ]
        ];
        
    }