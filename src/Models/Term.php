<?php 
	
	namespace WPKit\Models;
	
	use Illuminate\Database\Eloquent\Builder; 
	
	class Term extends Model {
	
	    /**
	     * Disable timestamps.
	     *
	     * @var boolean
	     */
	    public $timestamps = false;
	
	    /**
	     * The table associated with the model.
	     *
	     * @var string
	     */
	    protected $table = 'terms';
	    
	    /**
	     * The post_type associated with the model.
	     *
	     * @var string
	     */
	    protected $taxonomy = 'category';
	
	    /**
	     * The primary key for the model.
	     *
	     * @var string
	     */
	    protected $primaryKey = 'term_id';
	
	    /**
	     * The attributes that are mass assignable.
	     *
	     * @var array
	     */
	    protected $fillable = [
	        'name', 'slug'
	    ];
	    
	    /**
	     * Boot process.
	     *
	     * @return Void
	     */
		protected static function boot() {
		    parent::boot();
		    static::addGlobalScope('order', function (Builder $builder) {
		        $builder->orderBy('name', 'asc');
		    });
		}
	
	    /**
	     * Taxonomy relationship.
	     *
	     * @return \Illuminate\Database\Eloquent\Relations\HasMany
	     */
	    public function taxonomies()
	    {
	        $taxonomies = $this->hasMany(__NAMESPACE__ . '\Taxonomy', 'term_id');
	        if( $this->getTaxonomy() ) {
		        $taxonomies->where('taxonomy', $this->getTaxonomy());
	        }
	        return $taxonomies;
	    }
	    
	    /**
	     * Get the taxonomy of current instance
	     *
	     * @return string
	     */
	    public function getTaxonomy()
	    {
		    return $this->taxonomy;
	    }
	    
	    /**
	     * Where has posts based on args
	     *
	     * @return \Illuminate\Database\Eloquent\Builder
	     */
	    public function queryHasPosts( $query, $args = array() ) {
		    
		    $query->join(
    			'term_relationships as tr', 
    			'tr.term_taxonomy_id', '=', 'tt.term_taxonomy_id'
    		);
			
			$query->whereIn( 'tr.object_id', get_posts( array_merge( $args, array(
				'fields' => 'ids',
				'showposts' => -1
			) ) ) );
			
			$query->groupBy('terms.term_id');
			
			return $query;
		    
	    }
	    
	     /**
	     * Begin querying the model.
	     *
	     * @return \Illuminate\Database\Eloquent\Builder
	     */
	    public static function query()
	    {
		    $model = new static;
	        return parent::query()->select( 'terms.*' )->join('term_taxonomy as tt', function($join) use($model) {
		        
		        $join->on('tt.term_taxonomy_id', '=', 'terms.term_id');
		        $join->where('tt.taxonomy', '=', $model->getTaxonomy());
		        
	        });
	    }
	
	}
