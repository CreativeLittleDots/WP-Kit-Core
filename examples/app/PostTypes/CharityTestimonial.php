<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class CharityTestimonial extends PostType {
    
    var $slug = 'charity_testimonial';
    var $menu_name = 'Testimonials';
    var $supports = [ 'title', 'editor', 'thumbnail', 'revisions' ];
    var $icon = 'dashicons-heart';
	
}

?>