<?php 
	
	namespace WPKit\Models;

	use Carbon\Carbon;
	use Illuminate\Database\Eloquent\Builder; 
	use WPKit\Models\SoftDeletes\SoftDeletes;
	
	class Post extends Model {
	
	    use SoftDeletes;
	
	    /**
	     * The table associated with the model.
	     *
	     * @var string
	     */
	    protected $table = 'posts';
	    
	    /**
	     * The post_type associated with the model.
	     *
	     * @var string
	     */
	    protected $post_type = '';
	
	    /**
	     * The primary key for the model.
	     *
	     * @var string
	     */
	    protected $primaryKey = 'ID';
	
	    /**
	     * The name of the "created at" column.
	     *
	     * @var string
	     */
	    const CREATED_AT = 'post_date';
	
	    /**
	     * The name of the "updated at" column.
	     *
	     * @var string
	     */
	    const UPDATED_AT = 'post_modified';
	
	    /**
	     * The name of the "deleted at" column.
	     *
	     * @var string
	     */
	    const DELETED_AT = 'post_status';
	
	    /**
	     * The attributes that are mass assignable.
	     *
	     * @var array
	     */
	    protected $fillable = [
	        'post_date', 'post_date_gmt',
	        'post_content', 'post_title', 'post_excerpt',
	        'post_status', 'comment_status', 'ping_status',
	        'post_password', 'post_name',
	        'to_ping', 'pinged',
	        'post_modified', 'post_modified_gmt',
	        'post_content_filtered', 'guid', 'menu_order',
	        'post_type', 'post_mime_type',
	        'comment_count'
	    ];
	
	    /**
	     * The attributes that should be mutated to dates.
	     *
	     * @var array
	     */
	    protected $dates = [
	        'post_date', 'post_date_gmt',
	        'post_modified', 'post_modified_gmt'
	    ];
	    
	    /**
	     * The public_meta attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $public_meta = [];
		
		/**
	     * Boot process.
	     *
	     * @return Void
	     */
		protected static function boot() {
		    parent::boot();
		    $model = new static;
		    static::addGlobalScope('order', function (Builder $builder) use($model) {
		        $builder->orderBy('post_date', 'desc');
		        if( $model->getPostType() ) {
		        	$builder->where( 'post_type', $model->getPostType() );
		        }
		        if( $model->getPublicMeta() ) {
					$builder->with('meta');
				}
		    });
		}
	
	    /**
	     * Comment relationship.
	     *
	     * @return \Illuminate\Database\Eloquent\Relations\HasMany
	     */
	    public function comments()
	    {
	        return $this->hasMany(__NAMESPACE__ . '\Comment', 'comment_post_ID');
	    }
	
	    /**
	     * Taxonomy relationship.
	     *
	     * @return \Illuminate\Database\Eloquent\Relations\HasMany
	     */
	    public function taxonomies()
	    {
	        return $this->belongsToMany(__NAMESPACE__ . '\Taxonomy', 'term_relationships', 'object_id', 'term_taxonomy_id');
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
	
	    /**
	     * Get a specific type of post.
	     *
	     * @param $type
	     * @return $this
	     */
	    public static function type($type)
	    {
	        return static::query()
	            ->where('post_type', $type);
	    }
	
	    /**
	     * Set the value of the "created at" attribute.
	     *
	     * @param  mixed  $value
	     * @return void
	     */
	    public function setCreatedAt($value)
	    {
	        $this->{static::CREATED_AT} = $value;
	
	        if ( ! $value instanceof Carbon)
	        {
	            $value = new Carbon($value);
	        }
	
	        $this->{static::CREATED_AT . '_gmt'} = $value->timezone('GMT');
	    }
	
	    /**
	     * Set the value of the "updated at" attribute.
	     *
	     * @param  mixed  $value
	     * @return void
	     */
	    public function setUpdatedAt($value)
	    {
	        $this->{static::UPDATED_AT} = $value;
	
	        if ( ! $value instanceof Carbon)
	        {
	            $value = new Carbon($value);
	        }
	
	        $this->{static::UPDATED_AT . '_gmt'} = $value->timezone('GMT');
	    }
	    
	    /**
	     * Get the post type of current instance
	     *
	     * @return string
	     */
	    public function getPostType()
	    {
		    return $this->post_type;
	    }
	    
	    /**
	     * Get the public meta of current instance
	     *
	     * @return array
	     */
	    public function getPublicMeta()
	    {
		    return $this->public_meta;
	    }
	
	}
