<?php
	
	namespace WPKit\Models\Clean;
	
	use WPKit\Models\PostClass;
	
	class Post extends PostClass {
		
		/**
	     * The hidden attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $hidden = [
			'post_title',
			'post_status',
			'post_author', 
			'post_content',
			'post_excerpt',
			'comment_status',
			'ping_status',
			'post_password',
			'to_ping',
			'pinged',
			'post_content_filtered',
			'post_parent',
			'guid',
			'menu_order',
			'post_type',
			'post_mime_type',
			'comment_count',
			'post_date_gmt',
			'post_modified_gmt',
			'post_name',
			'post_date',
			'post_modified'
		];
		
		/**
	     * The appends attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $appends = [
			'title',
			'status',
			'date_added',
			'date_modified'
		];
		
		/**
	     * The fillable attributes that are mass assignable.
	     *
	     * @var array
	     */
	    protected $fillable = [
	        'post_title',
	        'post_status'
	    ];
	    
	    /**
	     * Get Title Attribute
	     *
	     * @var string
	     */
	    public function getTitleAttribute(){
		    return $this->attributes['post_title'];
		}
		
		/**
	     * Get Status Attribute
	     *
	     * @var string
	     */
		public function getStatusAttribute(){
		    return ! empty( $this->attributes['post_status'] ) ? $this->attributes['post_status'] : 'publish';
		}
		
		/**
	     * Get Date Added Attribute
	     *
	     * @var string
	     */
		public function getDateAddedAttribute(){
		    return $this->attributes['post_date'];
		}
		
		/**
	     * Get Date Modified Attribute
	     *
	     * @var string
	     */
		public function getDateModifiedAttribute(){
		    return $this->attributes['post_modified'];
		}
		
		/**
	     * PostMeta relationship.
	     *
	     * @return \Illuminate\Database\Eloquent\Relations\HasMany
	     */
	    public function meta()
	    {
		    $meta = $this->hasMany(__NAMESPACE__ . '\PostMeta', 'post_id');
		    if( ! empty( $this->public_meta ) ) {
				$meta->whereIn( 'meta_key', $this->public_meta );
			}
	        return $meta;
	    }
		
	}