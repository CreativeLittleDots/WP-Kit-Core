<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Career extends PostType {
    
    var $slug = 'career';
    var $supports = [ 'title', 'editor', 'revisions', 'thumbnail', 'excerpt', 'page-attributes' ];
    var $icon = 'dashicons-businessman';
	
}

?>