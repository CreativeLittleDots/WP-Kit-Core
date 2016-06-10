<?php

namespace App\PostTypes;

use WPKit\Core\PostType;

class Incentive extends PostType {
    
    var $slug = 'incentive';
    var $supports = [ 'title', 'revisions' ];
    var $icon = 'dashicons-awards';
	
}

?>