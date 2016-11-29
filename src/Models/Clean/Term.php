<?php 
	
	namespace WPKit\Models\Clean;
	
	use WPKit\Models\Term as TermClass;
	
	class Term extends TermClass {
		
		/**
	     * The hidden attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $hidden = [
			'term_id',
			'name',
			'slug',
			'term_group'
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
					'title'
				], $this->appends);
	    }
		
		/**
	     * Get Id Attribute
	     *
	     * @var string
	     */
	    public function getIdAttribute(){
		    return $this->attributes['term_id'];
		}
		
		/**
	     * Get Title Attribute
	     *
	     * @var string
	     */
	    public function getTitleAttribute(){
		    return $this->attributes['name'];
		}
	
	}
