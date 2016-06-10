<?php
    
    // set your invoked calls here
    
    wpkit()->invoke( 'AppController' );
    wpkit()->invoke( 'AjaxController' );
    wpkit()->invoke( 'FormController' );
    wpkit()->invoke( 'StoreController', 'is_shop', 'condition' );
    wpkit()->invoke( 'ProductController', 'is_product', 'condition' );
    wpkit()->invoke( 'AccountController', 'my-account', 'page' );
    wpkit()->invoke( 'CartController', 'woocommerce_cart_loaded_from_session' );
    wpkit()->invoke( 'ContactController', 'contact-us', 'page' );
    wpkit()->invoke( 'CheckoutController', 'is_checkout', 'condition' );
    
    wpkit()->invoke( 'EventController' );
    wpkit()->invoke( 'LoginController' );
    wpkit()->invoke( 'PostController' );
    wpkit()->invoke( 'UserController' );
    wpkit()->invoke( 'AdminController', 'admin_init' );
    wpkit()->invoke( 'FeedbackController', function() {
    	return is_page('Feedback');
    } );