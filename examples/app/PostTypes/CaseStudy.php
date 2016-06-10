<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class CaseStudy extends PostType {
    
    var $slug = 'case_study';
    var $supports = [ 'title', 'editor', 'author', 'custom-fields', 'thumbnail', 'excerpt', 'revisions' ];
    var $icon = 'dashicons-welcome-write-blog';
	
}

?>