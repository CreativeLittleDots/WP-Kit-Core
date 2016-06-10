<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Event extends PostType {
    
    var $slug = 'event';
    var $supports = [ 'title', 'editor', 'thumbnail', 'revisions', 'author' ];
    var $icon = 'dashicons-calendar-alt';
    var $has_archive = false;
	
}

?>