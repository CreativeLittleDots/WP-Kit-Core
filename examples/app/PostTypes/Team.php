<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Team extends PostType {
    
    var $slug = 'team';
    var $supports = [ 'title', 'author', 'editor', 'revisions', 'thumbnail', 'page-attributes' ];
    var $icon = 'dashicons-groups';
	
}

?>