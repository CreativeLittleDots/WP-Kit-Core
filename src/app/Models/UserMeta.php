<?php

	namespace WPKit\Models;
	
	class UserMeta extends Model {
		
		/**
	     * The table associated with the model.
	     *
	     * @var string
	     */
	    protected $primaryKey = 'meta_id';
	
		/**
	     * Disable timestamps.
	     *
	     * @var boolean
	     */
	    public $timestamps = false;
	    
	}