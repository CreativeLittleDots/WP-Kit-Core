<?php
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	class CartController extends Controller {
        
        public function beforeFilter()() {
	        			
			add_action( 'woocommerce_before_cart' , array($this, 'show_notification_if_cart_exceeded') );
			
			add_action( 'gettext', array($this, 'coupon_to_voucher') );
			
			add_filter( 'woocommerce_add_to_cart_fragments', array($this, 'cart_fragments') );
			
			parent::beforeFilter();
            
        }
		
		public function show_notification_if_cart_exceeded() {
		
			if( WC()->cart->cart_contents_total > 1500 ) {
			
				wc_add_notice( 'Did you know that can save money by purchasing on of our Cloud packages, view our <a href="/our-prices/">prices</a>.', 'notice');	
				
			}
			
		}
		
		public function coupon_to_voucher($text) {
			
			$text = str_replace('coupon', 'voucher', $text);
			$text = str_replace('Coupon', 'Voucher', $text);
			
			return $text;
			
		}
		
		public function cart_fragments($fragments) {
			
			$fragments['li.basket > a .itemCount'] = get_component( 'elements', 'basket-icon', array( 'count' => WC()->cart->get_cart_contents_count() ) );
			
			$fragments['li.basket > .drop'] = get_component( 'elements', 'basket-dropdown', array( 'cart' => WC()->cart ) );
			
			$url = WC()->cart->get_checkout_url();
			
			if( ! empty( $_POST['product_id'] ) ) {
			
				$items = WC()->cart->get_cart();
				
				wc_add_to_cart_message(end($items)['data']->id);
				
				$fragments['.footer-notification'] = get_component( 'elements', 'footer-notification' );
				
			}
			
			return $fragments;
			
		}
		
	}