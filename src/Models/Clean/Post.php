<?php
	
	namespace WPKit\Models\Clean;
	
	use WPKit\Models\Post as PostClass;
	
	class Post extends PostClass {
		
		/**
	     * The hidden attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $hidden = [
			'ID',
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
	     * Create a new Eloquent model instance.
	     *
	     * @param  array  $attributes
	     * @return void
	     */
	    public function __construct(array $attributes = [])
	    {
	        parent::__construct($attributes);
		        $this->appends = array_merge([
					'id',
					'title',
					'content',
					'url',
					'author_id',
					'blog_id',
					'thumbnail_id', 
					'status',
					'date_added',
					'date_modified'
				], $this->appends);
	    }
	    
	    /**
	     * Get Id Attribute
	     *
	     * @var string
	     */
	    public function getIdAttribute(){
		    return $this->attributes['ID'];
		}
		
		/**
	     * Get Author Id Attribute
	     *
	     * @var string
	     */
	    public function getAuthorIdAttribute(){
		    return $this->attributes['post_author'];
		}
		
		/**
	     * Get Blog Id Attribute
	     *
	     * @var string
	     */
	    public function getBlogIdAttribute(){
		    return ! empty( $this->attributes['blog_id'] ) ? $this->attributes['blog_id'] : null;
		}
		
		/**
	     * Get Thumbnail Id Attribute
	     *
	     * @var string
	     */
	    public function getThumbnailIdAttribute(){
		    return get_post_thumbnail_id( $this->ID );
		}
	    
	    /**
	     * Get Title Attribute
	     *
	     * @var string
	     */
	    public function getTitleAttribute(){
		    return $this->attributes['post_title'];
		}
		
		/**
	     * Get Url Attribute
	     *
	     * @var string
	     */
	    public function getUrlAttribute(){
		    return get_permalink( $this->ID );
		}
		
		/**
	     * Get Content Attribute
	     *
	     * @var string
	     */
	    public function getContentAttribute(){
		    return $this->attributes['post_content'];
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