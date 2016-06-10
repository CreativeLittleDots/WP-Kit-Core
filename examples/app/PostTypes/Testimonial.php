<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Testimonial extends PostType {
    
    var $slug = 'testimonial';
    var $supports = [ 'title', 'editor', 'author', 'revisions' ];
    var $icon = 'dashicons-heart';
	
}

?>