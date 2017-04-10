<?php

	namespace WPKit\Models;
	
	class User extends Model {
		
	    protected $primaryKey = 'ID';
	    protected $timestamp = false;
	
	    public function meta() {
		    
	        return $this->hasMany('WPKit\Models\UserMeta', 'user_id');
	        
	    }
	    
	}