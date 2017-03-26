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
	     * @var string/array
	     */
	    protected $post_type = '';
	    
	    /**
	     * The post_status associated with the model.
	     *
	     * @var string
	     */
	    protected $post_status = 'publish';
	
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
	     * The magic_meta attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $magic_meta = [];
		
		/**
	     * Boot process.
	     *
	     * @return Void
	     */
		protected static function boot() {
		    parent::boot();
		    $model = new static;
		    if( $model->getPostType() ) {
			    static::addGlobalScope('post_type', function (Builder $builder) use($model) {
				    if(is_array($model->getPostType())) {
			        	$builder->whereIn( 'post_type', $model->getPostType() );
			        } else {
				        $builder->where( 'post_type', $model->getPostType() );
			        }
			    });
			}
			if( $model->post_status ) {
				static::addGlobalScope('post_status', function (Builder $builder) use($model) {
				    $builder->where( 'post_status', $model->post_status );
			    });
			}
			static::addGlobalScope('order', function (Builder $builder) {
		        $builder->orderBy('post_date', 'desc');
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
		    return $this->hasMany(__NAMESPACE__ . '\PostMeta', 'post_id');
	    }
	    
	    /**
	     * Get Meta
	     *
	     * @return string
	     */
		public function getMeta($meta_key) 
		{			
			return $this->meta()->where('meta_key', $meta_key);	
		}
	    
	    /**
	     * Get Meta Value
	     *
	     * @return string
	     */
		public function getMetaValue($meta_key) 
		{			
			$meta = $this->getMeta($meta_key)->first();
			return $meta ? maybe_unserialize($this->getMeta($meta_key)->first()->getValue()) : null;	
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
	     * Get the magic meta of current instance
	     *
	     * @return array
	     */
	    public function getMagicMeta() 
	    {
		    return $this->magic_meta;
	    }
	    
	    /**
	     * Get the magic meta keys of current instance
	     *
	     * @return array
	     */
	    public function getMagicMetaKeys() 
	    {
		    return array_keys($this->getMagicMeta());
	    }
	    
	    /**
	     * Get the magic meta keys of current instance
	     *
	     * @return array
	     */
	    public function getMagicMetaFlipped() 
	    {
		    return array_flip($this->getMagicMeta());
	    }
	    
	    /**
	     * Get a magic meta key of current instance
	     *
	     * @return array
	     */
	    public function getMagicMetaKey($key) 
	    {
		    $magic_meta = $this->getMagicMetaFlipped();
		    return ! empty($magic_meta[$key]) ? $magic_meta[$key] : null;
	    }
	    
	    /**
	     * Get a magic meta key of current instance
	     *
	     * @return PostMeta
	     */
	    public function updateMetaValue($meta_key, $meta_value) {
		    if( $meta = $this->getMeta($meta_key)->first() ) {
			    return $meta->updateValue($meta_value);
			} else {
				$meta = new PostMeta(compact('meta_key', 'meta_value'));
				return $this->meta()->save($meta);
			}
	    }
	    
	    /**
	     * Convert the model's attributes to an array.
	     *
	     * @return array
	     */
	    public function attributesToArray()
	    {
		    $attributes = parent::attributesToArray();
		    foreach($this->getMagicMeta() as $meta_key => $key) {
			    $attributes[$key] = $this->getMetaValue($meta_key);
		    }
		    return $attributes;
		}
		
		/**
	     * Perform the actual delete query on this model instance.
	     *
	     * @return void
	     */
		public function delete() {
			parent::delete();
			wp_trash_post($this->ID);	
		}
		
		/**
	     * Perform the delete query on this model instance.
	     *
	     * @return void
	     */
		public function forceDelete() {
			parent::forceDelete();
			wp_delete_post($this->ID, true);	
		}
		
		/**
	     * Perform the save query on this model instance.
	     *
	     * @return void
	     */
		public function save(array $options = array()) {
			$post_type = $this->getPostType();
			$this->attributes['post_type'] = is_array($post_type) ? reset($post_type) : $post_type;
			parent::save($options);	
		}
	
	}
