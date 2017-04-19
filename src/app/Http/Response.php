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
	        $this->sendContent();
	
	        return $this;
	    }
		
	}