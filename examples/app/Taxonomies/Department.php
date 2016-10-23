<?php

namespace App\Taxonomies;

use WPKit\Core\Taxonomy;

class Department extends Taxonomy {
    
    var $slug = 'department';
    var $post_types = array('person');
    var $hierarchical = true;
	
}

?>