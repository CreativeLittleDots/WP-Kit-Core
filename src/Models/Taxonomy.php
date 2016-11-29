<?php 
	
	namespace WPKit\Models;
	
	use Illuminate\Database\Eloquent\Builder; 
	
	class Taxonomy extends Model {
	
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
	    protected $table = 'term_taxonomy';
	    
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
	    protected $primaryKey = 'term_taxonomy_id';
	
	    /**
	     * The attributes that are mass assignable.
	     *
	     * @var array
	     */
	    protected $fillable = [
	        'taxonomy', 'description', 'count'
	    ];
	    
	    /**
	     * Boot process.
	     *
	     * @return Void
	     */
		protected static function boot() {
		    parent::boot();
		    static::addGlobalScope('order', function (Builder $builder) {
		        $builder->join('terms as t', 't.term_id', '=', 'term_taxonomy.term_id')->orderBy('t.name', 'asc');
		    });
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
	     * Begin querying the model.
	     *
	     * @return \Illuminate\Database\Eloquent\Builder
	     */
	    public static function query()
	    {
		    $model = new static;
	        return parent::query()->where( 'taxonomy', $model->getTaxonomy() )->with('term');
	    }
	
	}
