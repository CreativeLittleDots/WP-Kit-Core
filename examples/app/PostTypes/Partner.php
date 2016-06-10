<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Partner extends PostType {
    
    var $slug = 'partner';
    var $supports = [ 'title', 'editor', 'author', 'revisions', 'thumbnail', 'excerpt', 'page-attributes' ];
    var $icon = 'dashicons-networking';
	
}

?>