<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Person extends PostType {
    
    var $slug = 'person';
    var $supports = [ 'title', 'editor', 'thumbnail', 'revisions' ];
    var $icon = 'dashicons-groups';
	
}

?>