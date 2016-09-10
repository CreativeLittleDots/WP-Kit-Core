<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class CheckoutController extends Controller {
        
        public function beforeFilter() {
			
			add_action( 'woocommerce_before_checkout_form' , array($this, 'show_notification_if_cart_exceeded') );
			
			add_filter( 'woocommerce_payment_complete_order_status', array($this, 'payment_complete_order_status') );
			
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'remove_bank_transfer_if_customer') );
		
			add_action( 'woocommerce_thankyou_bacs', array($this, 'complete_order_if_admin') );
			
			parent::beforeFilter();
            
        }
		
		public function show_notification_if_cart_exceeded() {
		
			if( WC()->cart->cart_contents_total > 1500 ) {
			
				wc_add_notice( 'Did you know that can save money by purchasing on of our Cloud packages, view our <a href="/our-prices/">prices</a>.', 'notice');	
				
				
			}
			
		}
		
		public function payment_complete_order_status() {
			
			return 'completed';
			
		}
		
		public function remove_bank_transfer_if_customer($_available_gateways) {
					
			if( ! current_user_can('manage_options') ) {
				
				unset($_available_gateways['bacs']);
			
			}
			
			return $_available_gateways;
		}
		
		public function complete_order_if_admin( $order_id ) {
			
			if( current_user_can('manage_options') ) {
				
				$order = wc_get_order($order_id);
				
				$order->payment_complete();
				
			}
			
		}
		
	}