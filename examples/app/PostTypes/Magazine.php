<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Magazine extends PostType {
    
    var $slug = 'magazine';
    var $supports = [ 'title', 'editor', 'thumbnail', 'revisions', 'author' ];
    var $icon = 'dashicons-book';
	
}

?>