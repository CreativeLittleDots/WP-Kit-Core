<?php
	
	/**
	 * WP Kit : MCEDC Wordpress Framework (https://github.com/creativelittledots/WP-Kit)
	 * Copyright (c) Creative Little Dots (https://creativelittledots.co.uk)
	 *
	 * Licensed under The MIT License
	 * For full copyright and license information, please see the LICENSE.txt
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright     Creative Little Dots (https://creativelittledots.co.uk)
	 * @link          https://github.com/creativelittledots/WP-Kit WP Kit Project
	 * @since         1.0.0
	 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
	 */
	
	namespace App\Controllers;
	
	use WPKit\Core\Controller;
	
	
	/**
	 * ExampleController
	 *
	 */
	class ExampleController extends Controller {
    	
    	/**
	     * Scripts array
	     *
	     * @var Array
	     */
    	public static $scripts = [];
    	
    	/**
	     * get_scripts static method.
	     *
	     * @return Array
	     */
    	public static function get_scripts() {
	        
	        return parent::get_scripts();
			
		}
		
		/**
	     * Initialization hook method.
	     *
	     * @return void
	     */
		public function __construct() {
			
		}
		
	}
	