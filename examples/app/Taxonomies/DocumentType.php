<?php

namespace App\Taxonomies;

use WPKit\Core\Taxonomy;

class DocumentType extends Taxonomy {
    
    var $slug = 'document_type';
    var $post_types = array('document');
    var $rewrite = array('slug' => 'documents');
    var $hierarchical = true;
	
}

?>