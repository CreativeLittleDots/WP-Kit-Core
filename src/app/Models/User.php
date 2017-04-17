<?php

	namespace WPKit\Models;
	
	use Illuminate\Auth\Authenticatable;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Auth\Passwords\CanResetPassword;
	use WPKit\Auth\Access\Authorizable;
	use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
	use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
	use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
	
	class User extends Model implements
	    AuthenticatableContract,
	    AuthorizableContract,
	    CanResetPasswordContract
	{
	    use Authenticatable, Authorizable, CanResetPassword;
	    
	    /**
	     * The attributes that are mass assignable.
	     *
	     * @var array
	     */
	    protected $fillable = [
	        'user_email', 'password',
	    ];
	    /**
	     * The attributes that should be hidden for arrays.
	     *
	     * @var array
	     */
	    protected $hidden = [
	        'user_pass'
	    ];
	    
	    public function getAuthPassword() {
		    
		    return $this->user_pass;
		    
	    }
	    
	}

?>