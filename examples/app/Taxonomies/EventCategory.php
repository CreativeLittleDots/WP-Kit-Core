<?php

namespace App\Taxonomies;

use WPKit\Core\Taxonomy;

class EventCategory extends Taxonomy {
    
    var $slug = 'event_category';
    var $post_types = array('event');
    var $rewrite = array('slug' => 'events');
    var $hierarchical = true;
    
}