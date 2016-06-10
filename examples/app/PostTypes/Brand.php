<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Brand extends PostType {
    
    var $slug = 'brand';
    var $supports = [ 'title', 'editor', 'thumbnail', 'revisions' ];
    var $icon = 'dashicons-tag';
	
}

?>