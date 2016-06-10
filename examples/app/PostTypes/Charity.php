<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Charity extends PostType {
    
    var $slug = 'charity';
    var $supports = [ 'title', 'editor', 'thumbnail', 'revisions' ];
    var $icon = 'dashicons-heart';
	
}

?>