<?php 
	
	namespace WPKit\Models\Clean;
	
	use WPKit\Models\Taxonomy as TaxonomyClass;
	
	class Taxonomy extends TaxonomyClass {
	
		/**
	     * The hidden attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $hidden = [
			'term_taxonomy_id',
			'term_id',
			'taxonomy',
			'parent', 
			'count',
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
				], $this->appends);
	    }
		
		/**
	     * Get Id Attribute
	     *
	     * @var string
	     */
	    public function getIdAttribute(){
		    return $this->attributes['term_taxonomy_id'];
		}
		
		/**
	     * Post relationship.
	     *
	     * @return \Illuminate\Database\Eloquent\Relations\HasMany
	     */
	    public function posts()
	    {
	        return $this->belongsToMany(__NAMESPACE__ . '\Post', 'term_relationships', 'term_taxonomy_id', 'object_id');
	    }
	
	    /**
	     * Term relationship.
	     *
	     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	     */
	    public function term()
	    {
	        return $this->belongsTo(__NAMESPACE__ . '\Term', 'term_id');
	    }
	
	}
