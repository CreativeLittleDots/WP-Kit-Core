<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Document extends PostType {
    
    var $slug = 'document';
    var $supports = [ 'title', 'revisions' ];
    var $icon = 'dashicons-media-document';
    var $has_archive = false;
	
}

?>