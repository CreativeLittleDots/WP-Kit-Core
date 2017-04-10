<?php

	namespace WPKit\Models;
	
	class UserMeta extends Model {
		
	    protected $primaryKey = 'meta_id';
	
	    public $timestamps    = false;
	
	    public function getTable() {
		    
	        return $this->getConnection()->db->prefix . 'usermeta';
	        
	    }
	    
	}