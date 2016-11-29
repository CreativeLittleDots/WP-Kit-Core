<?php
	
	namespace WPKit\Models\Clean;
	
	use WPKit\Models\PostMeta as PostMetaClass;
	
	class PostMeta extends PostMetaClass {
		
		/**
	     * The hidden attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $hidden = [
			'meta_id',
			'post_id',
			'meta_key', 
			'meta_value'
		];
		
		/**
	     * The appends attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $appends = [
			'key',
			'value'
		];
		
		/**
	     * The fillable attributes that are mass assignable.
	     *
	     * @var array
	     */
	    protected $fillable = [
	        'meta_key',
	        'meta_value'
	    ];
	    
	    /**
	     * Get Key Attribute
	     *
	     * @var string
	     */
	    public function getKeyAttribute(){
		    return $this->attributes['meta_key'];
		}
		
		/**
	     * Get Value Attribute
	     *
	     * @var string
	     */
		public function getValueAttribute(){
		    return maybe_unserialize( $this->attributes['meta_value'] );
		}
		
	}