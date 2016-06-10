<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Customer extends PostType {
    
    var $slug = 'customer';
    var $supports = [ 'title', 'author', 'revisions', 'thumbnail', 'page-attributes' ];
    var $icon = 'dashicons-universal-access';
	
}

?>