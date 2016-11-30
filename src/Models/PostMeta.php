<?php 
	
	namespace WPKit\Models;
	
	class PostMeta extends Model {
	
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
	    protected $table = 'postmeta';
	
	    /**
	     * The primary key for the model.
	     *
	     * @var string
	     */
	    protected $primaryKey = 'meta_id';
	
	    /**
	     * The attributes that are mass assignable.
	     *
	     * @var array
	     */
	    protected $fillable = [
	        'meta_key', 'meta_value'
	    ];
	
	    /**
	     * Post relationship.
	     *
	     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	     */
	    public function post()
	    {
	        return $this->belongsTo(__NAMESPACE__ . '\Post', 'post_id');
	    }
	    
	    /**
	     * Set the value of current postmeta instance
	     *
	     * @return PostMeta
	     */
	    public function setValue($value) 
	    {
		    $this->meta_value = $value;
		    return $this;
	    }
	    
	    /**
	     * Update the value of current postmeta instance
	     *
	     * @return PostMeta
	     */
	    public function updateValue($value) 
	    {
		    return $this->setValue($value)->save();
	    }
	    
	     /**
	     * Get the value of current postmeta instance
	     *
	     * @return string
	     */
	    public function getValue() 
	    {
		    return $this->meta_value;
	    }
	
	}
