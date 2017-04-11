<?php
	
	namespace WPKit\Auth;
	
	use Illuminate\Auth\SessionGuard as BaseSessionGuard;
	use WPKit\Http\Response;
	
	class SessionGuard extends BaseSessionGuard {
		
		/**
	     * Get the response for basic authentication.
	     *
	     * @return \Symfony\Component\HttpFoundation\Response
	     */
	    protected function failedBasicResponse()
	    {
	        return new Response('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
	    }
		
	}