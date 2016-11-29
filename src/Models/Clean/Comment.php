<?php 
	
	namespace WPKit\Models\Clean;
	
	use WPKit\Models\Comment as CommentClass;
	
	class Comment extends CommentClass {
	
	   /**
	     * The hidden attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $hidden = [
			'comment_ID',
			'comment_post_ID',
			'comment_author',
			'comment_author_email', 
			'comment_author_url',
			'comment_author_IP',
			'comment_date',
			'comment_date_gmt',
			'comment_content',
			'comment_karma',
			'comment_approved',
			'comment_agent',
			'comment_type',
			'comment_parent',
			'user_id',
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
					'post_id',
					'author',
					'author_email',
					'author_url',
					'date',
					'content', 
					'approved',
					'type',
					'parent_id'
				], $this->appends);
	    }   
		
		/**
	     * Get Post Id Attribute
	     *
	     * @var string
	     */
	    public function getIdAttribute(){
		    return $this->attributes['comment_ID'];
		}
		
		/**
	     * Get Id Attribute
	     *
	     * @var string
	     */
	    public function getPostIdAttribute(){
		    return $this->attributes['comment_post_ID'];
		}
		
		/**
	     * Get Author Attribute
	     *
	     * @var string
	     */
	    public function getAuthorAttribute(){
		    return $this->attributes['comment_author'];
		}
		
		/**
	     * Get Author Email Attribute
	     *
	     * @var string
	     */
	    public function getAuthorEmailAttribute(){
		    return $this->attributes['comment_author_email'];
		}
		
		/**
	     * Get Author Url Attribute
	     *
	     * @var string
	     */
	    public function getAuthorUrlAttribute(){
		    return $this->attributes['comment_author_url'];
		}
		
		/**
	     * Get Date Attribute
	     *
	     * @var string
	     */
	    public function getDateAttribute(){
		    return $this->attributes['comment_date'];
		}
		
		/**
	     * Get Content Attribute
	     *
	     * @var string
	     */
	    public function getContentAttribute(){
		    return $this->attributes['comment_content'];
		}
		
		/**
	     * Get Approved Attribute
	     *
	     * @var string
	     */
	    public function getApprovedAttribute(){
		    return $this->attributes['comment_approved'];
		}
		
		/**
	     * Get Type Attribute
	     *
	     * @var string
	     */
	    public function getTypeAttribute(){
		    return $this->attributes['comment_type'];
		}
		
		/**
	     * Get Parent Id Attribute
	     *
	     * @var string
	     */
	    public function getParentIdAttribute(){
		    return $this->attributes['comment_parent'];
		}
	
	}
