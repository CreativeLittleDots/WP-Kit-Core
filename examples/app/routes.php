<?php
    
    // set your invoked calls here
    
    invoke( 'AppController' );
    
    invoke( 'AjaxController' );
    
    invoke( 'FormController' );
    
    invoke( 'StoreController', 'wp', 'is_shop' );
    
    invoke( 'ProductController', 'wp', 'is_product' );
    
    invoke( 'AccountController', 'wp', function() {
	    
	    return is_page( 'my-account' );
	     
	});
	
    invoke( 'CartController', 'woocommerce_cart_loaded_from_session' );
    
    invoke( 'ContactController', 'wp', function() {
	 
		return is_page( 'contact-us' );   
	    
	} );
	
    invoke( 'CheckoutController', 'wp', 'is_checkout' );
    
    invoke( 'EventController' );
    
    invoke( 'LoginController' );
    
    invoke( 'PostController' );
    
    invoke( 'UserController' );
    
    invoke( 'AdminController', 'admin_init' );
    
    invoke( 'FeedbackController', 'wp', function() {
	    
    	return is_page('Feedback');
    	
    } );