<?php
	
	namespace WPKit\Http;
	
	use Illuminate\Http\Response as BaseResponse;
	
	class Response extends BaseResponse {
		
		/**
	     * Sends HTTP headers and content.
	     *
	     * @return $this
	     */
	    public function send()
	    {
	        $this->sendHeaders();
	        $this->sendContent();
	
	        return $this;
	    }
	    
	    public function sendHeaders() {
		    
		    if( ! is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		    
		    	parent::sendHeaders();
		    	
		    }
		    
	    }
		
	}